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
if (version_compare(PHP_VERSION, '5.2.4', '<')) {
    echo 'Error: You need at least PHP 5.2.4' . "\n";
    exit(1);
}
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
// we have to silence strict code warnings because of PEAR (see issue 642)
error_reporting(E_ALL & ~ E_STRICT);
require 'Console/Getopt.php';

global $debug;
$debug = false; // Yes a dirty global variable.
$search_path = null;

$cg = new Console_Getopt();
$shortoptions = 'aixubrc:v:d';
$longoptions = array(
        'app=',
        'version=',
        'conf=',
        'search-path=',
        'include-path='
);

$args = $cg->readPHPArgv();

function debug ($what)
{
    global $debug;
    if ($debug) {
        echo ($what . "\n");
    }
}

function usage ()
{
    echo 'Usage examples:' . "\n" .
             ' Upgrade all:     migrate.php --conf=path/to/config.php -a' . "\n" .
             ' Upgrade MyApp:   migrate.php --conf=path/to/config.php --app=MyApp' .
             "\n" .
             ' Backup MyApp:    migrate.php --conf=path/to/config.php --app=MyApp -b /path/to/backup/folder [backupname]' .
             "\n" .
             ' Restore MyApp:   migrate.php --conf=path/to/config.php --app=MyApp -r /path/to/backup/folder backupname' .
             "\n" .
             ' MyApp to ver. 3: migrate.php --conf=path/to/config.php --app=MyApp -v3' .
             "\n" . '' . "\n" . 'Options:' . "\n" .
             ' c, --conf:      Path to the configuration file.' . "\n" .
             ' a:              Upgrade all the installed applications.' . "\n" .
             ' v, --version:   Upgrade/Downgrade to the given version.' . "\n" .
             ' --app:          Application to upgrade/downgrade.' . "\n" .
             ' u:              Dry run, do nothing.' . "\n" .
             ' --search-path:  Set the DB search path before the run.' . "\n" .
             ' --include-path: Paths to add to the PHP include path.' . "\n" .
             ' d:              Display debug information.' . "\n" .
             ' i:              Install the application(s).' . "\n" .
             ' x:              Uninstall the application(s).' . "\n" .
             ' b:              Backup the application(s).' . "\n" .
             ' r:              Restore the application(s).' . "\n" . '' . "\n" .
             'Note: The command line parser of PEAR is not very robust' . "\n" .
             '      if you have an unexpected error about an offset not' . "\n" .
             '      set. Please report a bug. Thanks!' . "\n";
}

try {
    $ret = $cg->getopt($args, $shortoptions, $longoptions);
} catch (Exception $e) {
    echo ('Error in getopt command line: ' . $e->getMessage() . "\n");
    usage();
    die();
}

// Note that PEAR is not PHP 5 compatible, so the need to create $p.
$p = new PEAR();
if ($p->isError($ret)) {
    echo ('Error in command line: ' . $ret->getMessage() . "\n");
    usage();
    die();
}

// Parse the options.
$what = array(
        'all' => false,
        'app' => '',
        'conf' => '',
        'version' => null,
        'dry_run' => false,
        'un-install' => false,
        'install' => false,
        'backup' => false,
        'restore' => false
);

$opts = $ret[0];
$args = $ret[1];
if (sizeof($opts) > 0) {
    foreach ($opts as $o) {
        switch ($o[0]) {
            case 'a':
                $what['all'] = true;
                break;
            case 'b':
                $what['backup'] = true;
                break;
            case 'r':
                $what['restore'] = true;
                break;
            case 'v':
            case '--version':
                $what['version'] = $o[1];
                break;
            case 'c':
            case '--conf':
                $what['conf'] = $o[1];
                break;
            case '--app':
                $what['app'] = $o[1];
                break;
            case 'd':
                $debug = true;
                break;
            case '--search-path':
                $search_path = trim($o[1]);
                break;
            case '--include-path':
                set_include_path(get_include_path() . PATH_SEPARATOR .
                         trim($o[1]));
                break;
            case 'u':
                $what['dry_run'] = true;
                break;
            case 'x':
                $what['un-install'] = true;
                break;
            case 'i':
                $what['install'] = true;
                break;
        }
    }
} else {
    echo 'Error: Missing what to do.' . "\n";
    usage();
    die();
}

// control the arguments.
if (('' == $what['conf'] or ! file_exists($what['conf'])) or
         ($what['all'] == false and $what['app'] == '')) {
    echo 'Error: Missing what to do or config file.' . "\n";
    usage();
    die();
}
if ($what['all'] and $what['version'] !== null) {
    echo 'Error: -a and -v --version cannot be used together.' . "\n";
    echo '       Run the migration to a given version indenpendtly' . "\n";
    echo '       for each application.' . "\n";
    usage();
    die();
}

require 'Pluf.php';
Pluf::start($what['conf']);
if (PHP_SAPI != 'cli' and Pluf::f('migrate_allow_web', false)) {
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

if (! is_null($search_path)) {
    $db = & Pluf::db();
    $db->setSearchPath($search_path);
    debug('Set search path to: ' . $search_path);
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
if ($debug) {
    $m->display = true;
}
$m->dry_run = $what['dry_run'];

if ($what['install']) {
    debug('Install ' . $app_disp);
    $m->install();
} elseif ($what['un-install']) {
    debug('Uninstall ' . $app_disp);
    $m->uninstall();
} elseif ($what['backup']) {
    debug('Backup ' . $app_disp);
    if (! isset($args[1]))
        $args[1] = null;
    $m->backup($args[0], $args[1]);
} elseif ($what['restore']) {
    debug('Restore ' . $app_disp);
    $m->restore($args[0], $args[1]);
} else {
    debug('Migrate ' . $app . ' to version ' . $v_disp);
    $m->migrate($what['version']);
}



