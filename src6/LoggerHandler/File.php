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
namespace Pluf\LoggerHandler;

use Pluf\Logger;
use Pluf;

/**
 * ذخیره کردن لاگ‌ها در فایل
 *
 * This is the simplest logger. You can use it as a base to create
 * more complex loggers. The logger interface is really simple and use
 * some helper functions from the main <code>Pluf_Log</code> class.
 *
 * The only required static method of a log writer is
 * <code>write</code>, which takes the stack to write as parameter.
 *
 * The only configuration variable of the file writer is the path to
 * the log file 'pluf_log_file'. By default it creates a
 * <code>pluf.log</code> in the configured tmp folder.
 */
class File implements \Pluf\LoggerHandler
{

    public function write($stack = array()): void
    {
        $file = Pluf::f('pluf_log_file', Pluf::f('tmp_folder', '/tmp') . '/pluf.log');
        $out = array();
        foreach ($stack as $elt) {
            $out[] = date(DATE_ISO8601, (int) $elt[0]) . ' ' . Logger::$reverse[$elt[1]] . ': ' . json_encode($elt[2]);
        }
        file_put_contents($file, implode(PHP_EOL, $out) . PHP_EOL, FILE_APPEND);
    }
}
