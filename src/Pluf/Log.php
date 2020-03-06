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
 * Pluf Logger
 *
 * The removal of constraints on the log message simplify the log
 * system as you can push into it categories or extra informations.
 *
 * In the log stack, each log message is microtimed together with the
 * log level as integer. You can convert the integer to string at
 * write time.
 */
class Pluf_Log extends \Pluf\Logger
{

    /**
     * Signal handler to flush the log.
     *
     * The name of the signal and the parameters are not used.
     */
    public static function flushHandler($signal, &$params)
    {
        self::flush();
    }
}

/**
 * Assertion handler.
 *
 * @deprecated Use \Pluf\Logger::assert
 */
function Pluf_Log_assert($file, $line, $code): void
{
    Pluf_Log::assert($file, $line, $code);
}