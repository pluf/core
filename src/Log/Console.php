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
namespace Pluf\Log;

/**
 * Print log to console
 */
class Console
{

    /**
     * Flush the stack to the remote server.
     *
     * @param $stack Array
     */
    public static function write($stack)
    {
        foreach ($stack as $elt) {
            print(
                date(DATE_ISO8601, (int) $elt[0]) . ' ' . 
                \Pluf\Log::$reverse[$elt[1]] . ': ' . 
                $elt[2] .
                PHP_EOL);
            flush();
        }
    }
}
