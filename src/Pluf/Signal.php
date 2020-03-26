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

/**
 * Signal system.
 */
class Pluf_Signal
{

    /**
     * Send a signal.
     *
     * @param
     *            string Signal to be sent.
     * @param
     *            string Sender.
     * @param
     *            array Parameters
     * @return void
     */
    public static function send($signal, $sender, &$params = array())
    {
        if (! empty($GLOBALS['_PX_signal'][$signal])) {
            foreach ($GLOBALS['_PX_signal'][$signal] as /* $key=> */$val) {
                if ($val[2] === null or $sender == $val[2]) {
                    call_user_func_array(array(
                        $val[0],
                        $val[1]
                    ), array(
                        $signal,
                        &$params
                    ));
                }
            }
        }
    }

    /**
     * Connect to a signal.
     *
     * @param
     *            string Name of the signal.
     * @param
     *            array array('class', 'method') handling the signal.
     * @param
     *            string Optional sender filtering.
     */
    public static function connect($signal, $who, $sender = null)
    {
        if (! isset($GLOBALS['_PX_signal'][$signal])) {
            $GLOBALS['_PX_signal'][$signal] = array();
        }
        $GLOBALS['_PX_signal'][$signal][] = array(
            $who[0],
            $who[1],
            $sender
        );
    }
}

