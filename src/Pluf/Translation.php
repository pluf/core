<?php

/**
 * Translation utility. 
 *
 * This class provides utilities to load and cache translation
 * strings. The functions using the values are directly available when
 * loading Pluf. They are __ and _n for simple translations and for
 * plural dependent translations respectively.
 *
 * Based on benchmarking by
 * http://mel.melaxis.com/devblog/2006/04/10/benchmarking-php- \
 * localization-is-gettext-fast-enough/ the string id system is really
 * fast, so here the system is using a .ini file approach with a
 * string id cache.
 *
 * Why reimplementing a gettext system when one is already available?
 * It is because the PHP gettext extension requires the corresponding
 * locale to be installed system wide to load the corresponding
 * translations. If your host has no locales outside English
 * installed, you can only provide English to your users. Which is not
 * really nice.
 *
 */
class Pluf_Translation
{

    public static $plural_forms = array(
            'fr' => 'plural_2gt1',
            'en' => 'plural_2not1', // This is the default.
            'de' => 'plural_2not1'
    );

    public static function loadSetLocale ($lang)
    {
        $GLOBALS['_PX_current_locale'] = $lang;
        setlocale(LC_TIME, 
                array(
                        $lang . '.UTF-8',
                        $lang . '_' . strtoupper($lang) . '.UTF-8'
                ));
        if (isset($GLOBALS['_PX_locale'][$lang])) {
            return; // We consider that it was already loaded.
        }
        $GLOBALS['_PX_locale'][$lang] = array();
        foreach (Pluf::f('installed_apps') as $app) {
            if (false !=
                     ($pofile = Pluf::fileExists(
                            $app . '/locale/' . $lang . '/' . strtolower($app) .
                             '.po'))) {
                $GLOBALS['_PX_locale'][$lang] += Pluf_Translation::readPoFile(
                        $pofile);
            }
        }
    }

    public static function getLocale ()
    {
        return $GLOBALS['_PX_current_locale'];
    }

    /**
     * Get the plural form for a given locale.
     */
    public static function getPluralForm ($locale)
    {
        if (isset(self::$plural_forms[$locale])) {
            return self::$plural_forms[$locale];
        }
        if (isset(self::$plural_forms[substr($locale, 0, 2)])) {
            return self::$plural_forms[substr($locale, 0, 2)];
        }
        return 'plural_2not1';
    }

    /**
     * Return the "best" accepted language from the list of available
     * languages.
     *
     * Use $_SERVER['HTTP_ACCEPT_LANGUAGE'] if the accepted language
     * list is empty. The list must be something like:
     * 'da, en-gb;q=0.8, en;q=0.7'
     *
     * @param
     *            array Available languages in the system
     * @param
     *            string String of comma separated accepted languages ('')
     * @return string Language 2 or 5 letter iso code, default is 'en'
     */
    public static function getAcceptedLanguage ($available, $accepted = '')
    {
        if (empty($accepted)) {
            if (! empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $accepted = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            } else {
                return 'en';
            }
        }
        $acceptedlist = explode(',', $accepted);
        foreach ($acceptedlist as $lang) {
            $lang = explode(';', $lang);
            $lang = trim($lang[0]);
            if (in_array($lang, $available)) {
                return $lang;
            }
            // for the xx-XX cases we may have only the "main" language
            // form like xx is available
            $lang = substr($lang, 0, 2);
            if (in_array($lang, $available)) {
                return $lang;
            }
        }
        $langs = Pluf::f('languages', array(
                'en'
        ));
        return $langs[0];
    }

    /**
     * Given a key indexed array, do replacement using the %%key%% in
     * the string.
     */
    public static function sprintf ($string, $words = array())
    {
        foreach ($words as $key => $word) {
            $string = mb_ereg_replace('%%' . $key . '%%', $word, $string, 'm');
        }
        return $string;
    }

    /**
     * French, Brazilian Portuguese
     */
    public static function plural_2gt1 ($n)
    {
        return (int) ($n > 1);
    }

    public static function plural_2not1 ($n)
    {
        return (int) ($n != 1);
    }

    /**
     * Read a .
     * po file.
     *
     * Based on the work by Matthias Bauer with some little cosmetic fixes.
     *
     * @source
     * http://wordpress-soc-2007.googlecode.com/svn/trunk/moeffju/php-msgfmt/msgfmt-functions.php
     * 
     * @copyright 2007 Matthias Bauer
     * @author Matthias Bauer
     * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser
     *          General Public License 2.1
     * @license http://opensource.org/licenses/apache2.0.php Apache License 2.0
     */
    public static function readPoFile ($file)
    {
        if (false !== ($hash = self::getCachedFile($file))) {
            return $hash;
        }
        // read .po file
        $fc = file_get_contents($file);
        // normalize newlines
        $fc = str_replace(array(
                "\r\n",
                "\r"
        ), array(
                "\n",
                "\n"
        ), $fc);
        
        // results array
        $hash = array();
        // temporary array
        $temp = array();
        // state
        $state = null;
        $fuzzy = false;
        
        // iterate over lines
        foreach (explode("\n", $fc) as $line) {
            $line = trim($line);
            if ($line === '')
                continue;
            if (false === strpos($line, ' ')) {
                $key = $line;
                $data = '';
            } else {
                list ($key, $data) = explode(' ', $line, 2);
            }
            switch ($key) {
                case '#,': // flag...
                    $fuzzy = in_array('fuzzy', preg_split('/,\s*/', $data));
                case '#': // translator-comments
                case '#.': // extracted-comments
                case '#:': // reference...
                case '#|': // msgid previous-untranslated-string
                case '#~': // deprecated translations
                            // start a new entry
                    if (sizeof($temp) && array_key_exists('msgid', $temp) &&
                             array_key_exists('msgstr', $temp)) {
                        if (! $fuzzy)
                            $hash[] = $temp;
                        $temp = array();
                        $state = null;
                        $fuzzy = false;
                    }
                    break;
                case 'msgctxt':
                // context
                case 'msgid':
                // untranslated-string
                case 'msgid_plural':
                    // untranslated-string-plural
                    $state = $key;
                    $temp[$state] = $data;
                    break;
                case 'msgstr':
                    // translated-string
                    $state = 'msgstr';
                    $temp[$state][] = $data;
                    break;
                default:
                    if (strpos($key, 'msgstr[') !== False) {
                        // translated-string-case-n
                        $state = 'msgstr';
                        $temp[$state][] = $data;
                    } else {
                        // continued lines
                        switch ($state) {
                            case 'msgctxt':
                            case 'msgid':
                            case 'msgid_plural':
                                $temp[$state] .= "\n" . $line;
                                break;
                            case 'msgstr':
                                $temp[$state][sizeof($temp[$state]) - 1] .= "\n" .
                                         $line;
                                break;
                            default:
                                // parse error
                                return False;
                        }
                    }
                    break;
            }
        }
        
        // add final entry
        if ($state == 'msgstr')
            $hash[] = $temp;
            
            // Cleanup data, merge multiline entries, reindex hash for ksort
        $temp = $hash;
        $hash = array();
        foreach ($temp as $entry) {
            foreach ($entry as &$v) {
                $v = Pluf_Translation_poCleanHelper($v);
                if ($v === False) {
                    // parse error
                    return False;
                }
            }
            if (isset($entry['msgid_plural'])) {
                $hash[$entry['msgid'] . '#' . $entry['msgid_plural']] = $entry['msgstr'];
            } else {
                $hash[$entry['msgid']] = $entry['msgstr'];
            }
        }
        self::cacheFile($file, $hash);
        return $hash;
    }

    /**
     * Load optimized version of a language file if available.
     *
     * @return mixed false or array with value
     */
    public static function getCachedFile ($file)
    {
        $phpfile = Pluf::f('tmp_folder') . '/Pluf_L10n-' . md5($file) . '.phps';
        if (file_exists($phpfile) && (filemtime($file) < filemtime($phpfile))) {
            return include $phpfile;
        }
        return false;
    }

    /**
     * Cache an optimized version of a language file.
     *
     * @param
     *            string File
     * @param
     *            array Parsed hash
     */
    public static function cacheFile ($file, $hash)
    {
        $phpfile = Pluf::f('tmp_folder') . '/Pluf_L10n-' . md5($file) . '.phps';
        file_put_contents($phpfile, 
                '<?php return ' . var_export($hash, true) . '; ?>', LOCK_EX);
        @chmod($phpfile, 0666);
    }
}

function Pluf_Translation_poCleanHelper ($x)
{
    if (is_array($x)) {
        foreach ($x as $k => $v) {
            $x[$k] = Pluf_Translation_poCleanHelper($v);
        }
    } else {
        if ($x[0] == '"')
            $x = substr($x, 1, - 1);
        $x = str_replace("\"\n\"", '', $x);
        $x = str_replace('$', '\\$', $x);
        $x = @eval("return \"$x\";");
    }
    return $x;
}
