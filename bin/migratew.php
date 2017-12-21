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
function debug ($msg)
{
    global $what;
    if ($what['debug']) {
        echo ($msg . "\n");
    }
}

if (version_compare(PHP_VERSION, '5.2.4', '<')) {
    echo 'Error: You need at least PHP 5.2.4' . "\n";
    exit(1);
}

require_once 'Pluf.php';
Pluf::start($what['conf']);
if (PHP_SAPI != 'cli' and ! Pluf::f('migrate_allow_web', false)) {
    echo ('Error: This script can only be run from the command line.' . "\n");
    exit();
}

debug('PHP include path: ' . get_include_path());

if ($what['un-install']) {
    $apps = array();
    if ($what['all']) {
        $apps = Pluf::f('installed_apps');
    } else {
        $apps = array(
                $what['app']
        );
    }
    echo 'Applications to uninstall: ' . implode(', ', $apps) . "\n";
    echo 'Please confirm that you want to uninstall by typing "yes": ';
    $line = trim(fgets(STDIN)); // reads one line from STDIN
    if ($line != 'yes') {
        echo 'Abort...' . "\n";
        die();
    }
}

$app = null; // Migrate all the applications.
$app_disp = 'all the apps';
$v_disp = 'latest';
if (! is_null($what['version'])) {
    $v_disp = $what['version'];
}
if ($what['app']) {
    $app = trim($what['app']);
    $app_disp = $app;
}
$m = new Pluf_Migration($app);
if ($what['debug']) {
    $m->display = true;
}
$m->dry_run = $what['dry_run'];

if ($what['install']) {
    debug('# Install ' . $app_disp);
    $m->install();
} elseif ($what['un-install']) {
    debug('# Uninstall ' . $app_disp);
    $m->unInstall();
} elseif ($what['backup']) {
    debug('# Backup ' . $app_disp);
    if (! isset($args[1]))
        $args[1] = null;
    $m->backup($args[0], $args[1]);
} elseif ($what['restore']) {
    debug('# Restore ' . $app_disp);
    $m->restore($args[0], $args[1]);
} else {
    debug('# Migrate ' . $app . ' to version ' . $v_disp);
    $m->migrate($what['version']);
}

