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
 * Migration script.
 */
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
require 'Pluf.php';

function usage ()
{
    echo 'Usage examples:' . "\n" .
             ' Extract all:      extracttemplates.php path/to/config.php path/to/outpudir' .
             "\n";
}

function debug ($what)
{
    global $debug;
    if ($debug) {
        echo ($what . "\n");
    }
}

if ($argc !== 3) {
    usage();
    die();
}
$conf = $argv[1];
$outputdir = $argv[2];
Pluf::start($conf);
$generator = new Pluf_Translation_Generator();
$generator->generate($outputdir);
echo 'Done', "\n";