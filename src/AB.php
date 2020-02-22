<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pluf;

/**
 * Core  A/B testing component.
 *
 * The two importants methods are `test` and `convert`.
 *
 * For performance reasons, the A/B testing component requires you to
 * setup a cache (APC or Memcached) and use the MongoDB database. The
 * amount of data in the MongoDB should not be that big for most of
 * the websites and as such it is fine if you are using the 32bit
 * version of MongoDB.
 *
 * For the moment the storage is not abstracted to use another database.
 *
 * All the configuration variables for the component start with
 * `pluf_ab_`. You need to add 'Pluf_AB' to your list of middleware.
 *
 */
class AB
{
    /**
     * MongoDB database handler.
     */
    public static $db = null;

    /**
     * Returns an alternative for a given test.
     * 
     * The middleware is already storing the uid of the user and makes
     * it available as $request->pabuid.
     *
     * @param $test string Unique name of the test
     * @param $request \Pluf\HTTP\Request
     * @param $alts array Alternatives to pick from (array(true,false))
     * @param $weights array Weights for the alternatives (null)
     * @param $desc string Optional description of the test ('')
     * @return mixed One value from $alts
     */
    public static function test($test, &$request, $alts=array(true,false), 
                                $weights=null, $desc='')
    {
        if (Bootstrap::f('pluf_ab_allow_force', false) and 
            isset($request->GET[$test])) {
            return $alts[$request->GET[$test]];
        }
        $db = self::getDb();
        // Get or set test
        $dtest = $db->tests->findOne(array('_id' => $test), 
                                     array('_id', 'active', 'winner'));
        if ($dtest == null) {
            $dtest = array('_id' => $test,
                           'creation_dtime' => gmdate('Y-m-d H:i:s', 
                                                      $request->time),
                           'desc' => $desc,
                           'alts' => $alts,
                           'exp' => 0,
                           'conv' => 0,
                           'active' => true);
            for ($i=0;$i<count($alts);$i++) {
                $dtest['expalt_'.$i] = 0;
                $dtest['convalt_'.$i] = 0;
            }
            $db->tests->update(array('_id'=>$test), $dtest,
                               array('upsert' => true));
        } elseif (!$dtest['active']) {
            // If test closed with given alternative, returns alternative
            return (isset($dtest['winner'])) ? $alts[$dtest['winner']] : $alts[0];
        }
        if (!isset($request->pabuid)) {
            $request->pabuid = self::getUid($request);
        }
        if ($request->pabuid == 'bot') {
            return $alts[0];
        }
        // If $request->pabuid in test, returns corresponding alternative
        $intest = $db->intest->findOne(array('_id' => $test.'##'.$request->pabuid), 
                                      array('_id', 'alt'));
        if ($intest) {
            return $alts[$intest['alt']];
        }
        // Else find alternative, store and return it
        if ($weights == null) {
            $weights = array_fill(0, count($alts), 1.0/count($alts));
        }
        $alt = self::weightedRand($weights);
        $intest = array('_id' => $test.'##'.$request->pabuid, 
                        'test' => $test,
                        'pabuid' => $request->pabuid, 
                        'first_dtime' => gmdate('Y-m-d H:i:s', 
                                                $request->time),
                        'alt' => $alt);
        $db->intest->update(array('_id' => $test.'##'.$request->pabuid),
                            $intest, array('upsert' => true));
        // Update the counts of the test
        $db->tests->update(array('_id' => $test), 
                           array('$inc' => array('exp' => 1, 
                                                 'expalt_'.$alt => 1)));
        return $alts[$alt];
    }

    /**
     * Mark a test as converted.
     *
     * A user which was not exposed to the test or a bot is not marked
     * as converted as it is not significant.
     *
     * @param $test string Test
     * @param $request Pluf_HTTP_Request
     */
    public static function convert($test, $request)
    {
        if (!isset($request->pabuid) or $request->pabuid == 'bot') {
            return;
        }
        $db = self::getDb();
        $id = $test.'##'.$request->pabuid;
        $intest = $db->intest->findOne(array('_id' => $id), 
                                       array('_id', 'alt'));
        if (!$intest) {
            // Not tested
            return;
        }
        $conv = $db->convert->findOne(array('_id' => $id)); 
        if ($conv) {
            // Already converted
            return;
        }
        $dtest = $db->tests->findOne(array('_id' => $test)); 
        if (!$dtest or !$dtest['active']) {
            return;
        }
        $conv = array(
                      '_id' => $id,
                      'test' => $test,
                      );
        $db->convert->update(array('_id' => $id), $conv, 
                             array('upsert' => true));
        // increment the test counters
        $db->tests->update(array('_id' => $test), 
                           array('$inc' => array('conv' => 1, 
                                                 'convalt_'.$intest['alt'] => 1)));
    }

    /**
     * Register a property set for the user.
     *
     * This allows you to segment your users with these properties.
     *
     * @param $request Pluf_HTTP_Request
     * @param $props array Properties
     */
    public static function register(&$request, $props) 
    {
        $pabuid = (isset($request->pabuid)) ? 
            $request->pabuid : 
            self::getUid($request);
        if ($pabuid == 'bot') {
            return;
        }
        $request->pabuid = $pabuid;
        $request->pabprops = array_merge($request->pabprops, $props);
    }

    /**
     * Track a funnel.
     *
     * The array of properties can be used to track different A/B
     * testing cases.
     *
     * The list of properties must be the same at all the steps of the
     * funnel, you cannot pass array('gender' => 'M') at step 1 and
     * array('age' => 32) at step 2. You need to pass both of them at
     * all steps.
     *
     * @param $funnel string Name of the funnel
     * @param $step int Step in the funnel, from 1 to n
     * @param $stepname string Readable name for the step
     * @param $request Pluf_HTTP_Request Request object
     * @param $props array Array of properties associated with the funnel (array())
     */
    public static function trackFunnel($funnel, $step, $stepname, $request, $props=array())
    {
        $pabuid = (isset($request->pabuid)) ? 
            $request->pabuid : 
            self::getUid($request);
        if ($pabuid == 'bot') {
            return;
        }
        $request->pabuid = $pabuid;
        $cache = \Pluf\Cache::factory();
        $key = 'pluf_ab_funnel_'.crc32($funnel.'#'.$step.'#'.$pabuid);
        if ($cache->get($key, false)) {
            return; // The key is valid 60s not to track 2 steps within 60s
        }
        $cache->set($key, '1', 60);
        $what = array(
                      'f' => $funnel,
                      's' => $step,
                      'sn' => $stepname,
                      't' => (int) gmdate('Ymd', $request->time),
                      'u' => $pabuid,
                      'p' => array_merge($request->pabprops, $props),
                      );
        $db = self::getDb();
        $db->funnellogs->insert($what);
    }

    /**
     * Process the response of a view.
     *
     * If the request has no cookie and the request has a pabuid, set
     * the cookie in the response. 
     *
     * @param Pluf_HTTP_Request The request
     * @param Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response($request, $response)
    {
        if (!isset($request->COOKIE['pabuid']) and isset($request->pabuid)
             and $request->pabuid != 'bot') {
            $response->cookies['pabuid'] = $request->pabuid;
        }
        if (isset($request->pabprops) and count($request->pabprops) 
            and $request->pabuid != 'bot') {
            $response->cookies['pabprops'] = Sign::dumps($request->pabprops, null, true);
        }
        return $response;
    }

    /**
     * Process the request.
     *
     * If the request has the A/B test cookie, set $request->pabuid.
     *
     * @param Pluf_HTTP_Request The request
     * @return bool False
     */
    function process_request($request)
    {
        if (isset($request->COOKIE['pabuid']) and
            self::check_uid($request->COOKIE['pabuid'])) {
            $request->pabuid = $request->COOKIE['pabuid'];
        }
        $request->pabprops = array();
        if (isset($request->COOKIE['pabprops'])) {
            try {
                $request->pabprops = Pluf_Sign::loads($request->COOKIE['pabprops']);
            } catch (Exception $e) {
            }
        }
        return false;
    }

    /**
     * Get a MongoDB database handle.
     *
     * It opens only one connection per request and tries to keep a
     * persistent connection between the requests.
     *
     * The configuration keys used are:
     *
     * `pluf_ab_mongo_server`: 'mongodb://localhost:27017'
     * `pluf_ab_mongo_options`: array('connect' => true, 
     *                                'persist' => 'pluf_ab_mongo')
     * `pluf_ab_mongo_db`: 'pluf_ab'
     *
     * If you have a default installation of MongoDB, it should work
     * out of the box. 
     *
     */
    public static function getDb()
    {
       if (self::$db !== null) {
            return self::$db;
        }
        $server = Pluf::f('pluf_ab_mongo_server', 'mongodb://localhost:27017');
        $options = Pluf::f('pluf_ab_mongo_options', 
                           array('connect' => true, 'persist' => 'pluf_ab_mongo'));
        $conn = new Mongo($server, $options); 
        self::$db = $conn->selectDB(Pluf::f('pluf_ab_mongo_db', 'pluf_ab'));
        return self::$db;
    }
    
    /**
     * Get the uid of a given request.
     *
     * @param $request Pluf_HTTP_Request
     */
    public static function getUid($request)
    {
        if (isset($request->COOKIE['pabuid']) and 
            self::check_uid($request->COOKIE['pabuid'])) {
            return $request->COOKIE['pabuid'];
        }
        if (!isset($request->SERVER['HTTP_USER_AGENT']) or
            self::isBot($request->SERVER['HTTP_USER_AGENT'])) {
            return 'bot';
        }
        // Here we need to make an uid, first check if a user with
        // same ip/agent exists and was last seen within the last 1h.
        // We get that from MemcacheDB
        $cache = \Pluf\Cache::factory();
        $key = 'pluf_ab_'.crc32($request->remote_addr.'#'.$request->SERVER['HTTP_USER_AGENT']);
        if ($uid=$cache->get($key, null)) {
            $cache->set($key, $uid, 3600);
            return $uid;
        }
        $uid = self::make_uid($request);
        $cache->set($key, $uid, 3600);
        return $uid;
    }

    /**
     * Check if a given user agent is a bot.
     *
     * @param $user_agent string User agent string
     * @return bool True if the user agent is a bot
     */
    public static function isBot($user_agent)
    {
        static $bots = array('robot', 'checker', 'crawl', 'discovery', 
                             'hunter', 'scanner', 'spider', 'sucker', 'larbin',
                             'slurp', 'libwww', 'lwp', 'yandex', 'netcraft',
                             'wget', 'twiceler');
        static $pbots = array('/bot[\s_+:,\.\;\/\\\-]/i', 
                              '/[\s_+:,\.\;\/\\\-]bot/i');
        foreach ($bots as $r) {
            if (false !== stristr($user_agent, $r)) {
                return true;
            }
        }
        foreach ($pbots as $p) {
            if (preg_match($p, $user_agent)) {
                return true;
            }
        }
        if (false === strpos($user_agent, '(')) {
            return true;
        }
        return false;
    }

    /**
     * Returns a random weighted alternative.
     *
     * Given a series of weighted alternative in the format:
     * 
     * <pre>
     * array('alt1' => 0.2,
     *       'alt2' => 0.3,
     *       'alt3' => 0.5);
     * </pre>
     *
     * Returns the key of the selected alternative. In the following
     * example, the alternative 3 (alt3) has a 50% chance to be
     * selected, if the selected the results would be 'alt3'.

     * @link: http://20bits.com/downloads/w_rand.phps
     *
     * @param $weights array Weighted alternatives
     * @return mixed Key of the selected $weights array
     */
    public static function weightedRand($weights) 
    {
        $r = mt_rand(1,10000);
        $offset = 0;
        foreach ($weights as $k => $w) {
            $offset += $w*10000;
            if ($r <= $offset) {
                return $k;
            }
        }
    }

    /**
     * Given a request, make a corresponding A/B test UID.
     *
     * The UID is based on the time, the remote address, a random
     * component and is hashed to ensure the integrity and avoid the
     * need of a database hit when controlled.
     *
     * @param $request Pluf_HTTP_Request
     * @return string UID
     */
    public static function make_uid($request)
    {
        $base = sprintf('%08X%08X%08X', $request->time, 
                        sprintf('%u', crc32($request->remote_addr)), 
                        rand());
        return sprintf('%s%08X', $base, sprintf('%u', crc32($base.md5(Pluf::f('secret_key')))));
    }

    /**
     * Validate the uid in the cookie. 
     *
     * @see self::make_uid
     *
     * @param $uid string The UID
     * @return bool True if the UID is valid
     */
    public static function check_uid($uid)
    {
        if (strlen($uid) != 32) {
            return false;
        }
        $check = sprintf('%08X', sprintf('%u', crc32(substr($uid, 0, 24).md5(Pluf::f('secret_key')))));
        return  ($check == substr($uid, -8));
    }

    /* ------------------------------------------------------------
     *
     *                  Statistics Functions
     *
     * Note: I am not a statistician, use at your own risk!
     *
     * ------------------------------------------------------------ */

    /**
     * Given a conversion rate calculate the recommended sample sizes.
     *
     * The sample sizes is calculated to be significant at 95% in the
     * case of a variation of conversion with respect to the other
     * alternative of 25%, 15% and 5%.
     *
     * @param $conv Conversion rate ]0.0;1.0]
     * @return array The 3 sample sizes for 25%, 15% and 5%
     */
    public static function ssize($conv)
    {
        $a = 3.84145882689; // $a = pow(inverse_ncdf(1-(1-0.95)/2),2)
        $res = array();
        $bs = array(0.0625, 0.0225, 0.0025);
        foreach ($bs as $b) {
            $res[] = (int) ((1-$conv)*$a/($b*$conv));
        }
        return $res;
    }


    /**
     * Given a test, returns the corresponding stats.
     *
     * @param $test array Test definition and results
     * @return array Statistics for the test
     */
    public static function getTestStats($test)
    {
        $stats = array(); // Will store the stats
        $n = count($test['alts']);
        $aconvr = array(); // All the conversion rates to sort the alternatives
        for ($i=0;$i<$n;$i++) {
            $conv = (isset($test['convalt_'.$i])) ? $test['convalt_'.$i] : 0;
            $exp = (isset($test['expalt_'.$i])) ? $test['expalt_'.$i] : 0;
            $convr = self::cr(array($exp, $conv));
            $nconvr =  ($convr !== null) ?
                sprintf('%01.2f%%', $convr*100.0) : 'N/A';
            $ssize = ($convr !== null and $convr > 0) ?
                self::ssize($convr) : array();
            $stats[] = array('alt' => $i,
                             'convr' => $convr,
                             'conv' => $conv,
                             'exp' => $exp,
                             'nconvr' => $nconvr,
                             'ssize' => $ssize);
            $aconvr[] = ($convr === null) ? 0 : $convr;
        }
        array_multisort($aconvr, SORT_DESC, $stats);
        // We want the best to be significantly better than the second best.
        for ($i=0;$i<$n;$i++) {
            $convr = $stats[$i]['convr'];
            $exp = $stats[$i]['exp'];
            $conv = $stats[$i]['conv'];
            $comp = false;
            $zscore = false;
            $conf = false;
            $better = false;
            if ($i != 1 and $stats[1]['convr'] > 0) {
                // Compare with base case and get confidence/Z-score
                $comp = 100.0 * (float) ($convr - $stats[1]['convr'])/ (float) ($stats[1]['convr']);
                if ($comp > 0) $better = true;
                $comp = sprintf('%01.2f%%', $comp);                
                $zscore = self::zscore(array($stats[1]['exp'], $stats[1]['conv']), 
                                       array($exp, $conv));
                $conf = sprintf('%01.2f%%', self::cumnormdist($zscore)*100.0);
                $zscore = sprintf('%01.2f', $zscore);
            }
            $stats[$i]['comp'] = $comp;
            $stats[$i]['zscore'] = $zscore;
            $stats[$i]['conf'] = $conf;
            $stats[$i]['better'] = $better;
        }
        return $stats;
    }

    public static function cr($t) 
    { 
        if ($t[1] < 0) return null;
        if ($t[0] <= 0) return null;
        return $t[1]/$t[0]; 
    }

    public static function zscore($c, $t) 
    {
        $z = self::cr($t)-self::cr($c);
        $s = (self::cr($t)*(1-self::cr($t)))/$t[0] 
            + (self::cr($c)*(1-self::cr($c)))/$c[0];
        return $z/sqrt($s);
    }

    /**
     * Approximation of the cumulative normal distribution.
     */
    public static function cumnormdist($x)
    {
        $b1 =  0.319381530;
        $b2 = -0.356563782;
        $b3 =  1.781477937;
        $b4 = -1.821255978;
        $b5 =  1.330274429;
        $p  =  0.2316419;
        $c  =  0.39894228;

        if($x >= 0.0) {
            $t = 1.0 / ( 1.0 + $p * $x );
            return (1.0 - $c * exp( -$x * $x / 2.0 ) * $t *
                    ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
        } else {
            $t = 1.0 / ( 1.0 - $p * $x );
            return ( $c * exp( -$x * $x / 2.0 ) * $t *
                     ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
        }
    }
}
