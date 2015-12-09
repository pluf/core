<?php

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


require 'Pluf.php';
Pluf::start($what['conf']);
if (PHP_SAPI != 'cli' and !Pluf::f('migrate_allow_web', false)) {
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

