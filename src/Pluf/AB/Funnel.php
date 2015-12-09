<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Funnel statistics.
 *
 * Funnels are easy to track but not that easy to generate statistics
 * out of them.
 *
 * Stats are compiled "by GMT day", so you can track your funnel per
 * day, week or more. Stats are put in cache in the "funnels" collection.
 *
 */
class Pluf_AB_Funnel
{
    /**
     * Returns the list of funnels.
     *
     * @return array Funnels
     */
    public static function getFunnels()
    {
        $db = Pluf_AB::getDb();
        foreach (array('f', 't') as $k) {
            // Once created, it will return immediately in the future
            // calls so the overhead is negligeable.
            $db->funnellogs->ensureIndex(array($k => 1), 
                                         array('background' => true));
        }
        $nf = $db->command(array('distinct' => 'funnellogs', 'key' => 'f'));
        if ((int) $nf['ok'] == 1) {
            sort($nf['values']);
            return $nf['values'];
        }
        return array();
    }

    /**
     * Get all the properties for a given period.
     */
    public static function getFunnelProps($funnel, $period='today')
    {
        $db = Pluf_AB::getDb();
        switch ($period) {
        case 'yesterday':
            $q = array('t' => (int) gmdate('Ymd', time()-86400));
            break;
        case 'today':
            $q = array('t' => (int) gmdate('Ymd'));
            break;
        case '7days':
            $q = array('t' => array('$gte' => (int) gmdate('Ymd', time()-604800)));
            break;
        case 'all':
        default:
            $q = array();
            break;
        }
        $q['f'] = $funnel;
        $props = array();
        foreach ($db->funnellogs->find($q) as $log) {
            foreach ($log['p'] as $prop => $v) {
                if (isset($props[$prop]) and !in_array($v, $props[$prop])) {
                    $props[$prop][] = $v;
                } else {
                    $props[$prop] = array($v);
                }
            }
        }
        return $props;
    }

    /**
     * Get stats for a given funnel.
     *
     * @param $funnel string Funnel
     * @param $period string Time period 'yesterday', ('today'), '7days', 'all'
     * @param $prop string Property to filter (null)
     */
    public static function getStats($funnel, $period='today', $prop=null)
    {
        $db = Pluf_AB::getDb();
        $steps = array();
        for ($i=1;$i<=20;$i++) {
            $steps[$i] = array();
        }
        switch ($period) {
        case 'yesterday':
            $q = array('t' => (int) gmdate('Ymd', time()-86400));
            break;
        case 'today':
            $q = array('t' => (int) gmdate('Ymd'));
            break;
        case '7days':
            $q = array('t' => array('$gte' => (int) gmdate('Ymd', time()-604800)));
            break;
        case 'all':
        default:
            $q = array();
            break;
        }
        $q['f'] = $funnel;
        if ($prop) {
            $q['p.'.$prop] = array('$exists' => true);
        }
        $uids = array();
        // With very big logs, we will need to find by schunks, this
        // will be very easy to adapt.
        foreach ($db->funnellogs->find($q) as $log) {
            if (!isset($uids[$log['u'].'##'.$log['s']])) {
                if ($prop and !isset($log['p'][$prop])) {
                    continue;
                }
                $uids[$log['u'].'##'.$log['s']] = true;
                $step = $log['s'];
                $steps[$step]['name'] = $log['sn'];
                $steps[$step]['id'] = $log['s'];
                if ($prop and !isset($steps[$step]['props'])) {
                    $steps[$step]['props'] = array();
                }
                $steps[$step]['total'] = (isset($steps[$step]['total'])) ?
                    $steps[$step]['total'] + 1 : 1;
                if ($prop) {
                    $steps[$step]['props'][$log['p'][$prop]] = (isset($steps[$step]['props'][$log['p'][$prop]])) ?
                        $steps[$step]['props'][$log['p'][$prop]] + 1 : 1;
                }
            }
        }
        // Now, compile the stats for steps 2 to n
        // First, we find the "max" for the reference number of
        // visitors along this funnel. This is $t1 and $tprops[prop]
        $t1 = 0;
        foreach ($steps as $step) {
            if (isset($step['total']) and $step['total'] > $t1) {
                $t1 = $step['total'];
            }
            if ($prop and isset($step['props'])) {
                foreach ($step['props'] as $v => $t) {
                    if (!isset($tprops[$v])) {
                        $tprops[$v] = $t;
                        continue;
                    }
                    if ($tprops[$v] < $t) {
                        $tprops[$v] = $t; 
                    }
                }
            }
        }
        if ($t1 == 0) {
            return array();
        }
        $prev_step = null;
        for ($i=1;$i<=20;$i++) {
            if ($prev_step == null) {
                
            }
            if ($steps[$i]) {
                $tp = $prev_step['total'];
                $tn = $steps[$i]['total'];
                if ($tp) {
                    $steps[$i]['conv'] = sprintf('%01.2f%%', 100.0 - (float)($tp-$tn)/$tp*100.0);
                } else {
                    $steps[$i]['conv'] = 'N/A';
                }
                if ($t1) {
                    $steps[$i]['conv1'] = sprintf('%01.2f%%', 100.0 - (float)($t1-$tn)/$t1*100.0);
                } else {
                    $steps[$i]['conv1'] = 'N/A';
                }
                if ($prop) {
                    $steps[$i]['sprops'] = array();
                    $steps[$i]['sprops1'] = array();
                    foreach ($steps[$i]['props'] as $v => $t) {
                        if (!isset($tprops[$v])) {
                            $tprops[$v] = $t;
                        }
                        $pv = isset($prev_step['props'][$v]) ? $prev_step['props'][$v] : 0;
                        $steps[$i]['sprops'][$v] = array($t, $pv);
                        $steps[$i]['sprops1'][$v] = array($t, $tprops[$v]);
                        if ($pv) {
                            $steps[$i]['sprops'][$v][] = round(100*(float)$t/(float)$pv,2).'%';
                        } else {
                            $steps[$i]['sprops'][$v][] = 0;
                        }
                        $steps[$i]['sprops1'][$v][] = round(100*(float)$t/(float)$tprops[$v],2).'%';
                    }
                }
                $steps[$i]['bigtotal'] = $t1;
                $prev_step = $steps[$i];
            }
        }
        for ($i=20;$i>=1;$i--) {
            if (!$steps[$i]) {
                unset($steps[$i]); // We remove the inexisting steps
            } 
        }
        return $steps;
    }
}
