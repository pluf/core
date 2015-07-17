<?php
/**
 * فرآیند اجرای تست
 * 
 */
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));

function usage ($script)
{
    echo ($script . ' YourApp [path/to/config/file.php]' . "\n");
    echo (' If no config file given, will use the following:' . "\n");
    echo (' YourApp/conf/yourapp.test.php' . "\n");
    echo (' If you are using SQLite, we recommend the ":memory:" database.' .
             "\n");
    echo (' [installed apps]/Migrations/Install.php will be used to setup/teardown' .
             "\n");
    echo (' the database before/after the run.' . "\n");
}

function e ($m)
{
    echo ($m . "\n");
}

function getTestDirs ($dir = './')
{
    $file = new DirectoryIterator($dir);
    $res = array();
    while ($file->valid()) {
        if ($file->isDir() && ! $file->isDot()) {
            $res[] = $file->getPathName();
        }
        $file->next();
    }
    return $res;
}

function getTestFiles ($dir = '')
{
    $file = new DirectoryIterator($dir);
    $res = array();
    while ($file->valid()) {
        if ($file->isFile() && substr($file->getPathName(), - 4) == '.php') {
            $class = str_replace(DIRECTORY_SEPARATOR, '_', 
                    substr($file->getPathName(), 0, - 4));
            $res[] = array(
                    $file->getPathName(),
                    $class
            );
        }
        $file->next();
    }
    return $res;
}
if (PHP_SAPI == 'cli') {
    // Get the application and then the configuration file
    if ($argc < 2) {
        usage($argv[0]);
        exit(1);
    }
    $app = $argv[1];
    if ($argc >= 3) {
        $config = $argv[2];
    } else {
        $config = $app . '/conf/' . strtolower($app) . '.test.php';
    }
} else {
    echo ('Error: This script can only be run from the command line.' . "\n");
    exit(1);
}
echo (sprintf('Application: %s ', $app));
if (! file_exists($config)) {
    echo (sprintf("\n" . 'Error, the config file does not exists: %s' . "\n", 
            $config));
    exit(1);
} else {
    echo (sprintf('(%s)' . "\n", $config));
}
define('IN_UNIT_TESTS', true);
require 'Pluf.php';
Pluf::start($config);

$simple_test = Pluf::f('simple_test_path', false);
if (false == $simple_test) {
    e('Error, the path to the simple test framework is not defined.');
    e('Download simple test from:');
    e('   http://simpletest.sourceforge.net/');
    e('Extract the archive on your system and set the "simple_test_path"');
    e('configuration variable in your configuration file.');
    e('For example: $cfg[\'simple_test_path\'] = \'/home/you/simpletest\'; ');
    exit(1);
}
$testfolder = $app . '/Tests/';
if (! file_exists($testfolder)) {
    e(sprintf('The test folder does not exists: %s.', $app . '/Tests/'));
    exit(1);
}

define('SIMPLE_TEST', $simple_test . '/');
require_once (SIMPLE_TEST . 'unit_tester.php');
require_once (SIMPLE_TEST . 'reporter.php');

$files = getTestFiles($testfolder);
$dirs = getTestDirs($testfolder);
foreach ($dirs as $dir) {
    foreach (getTestFiles($dir) as $test) {
        $files[] = $test;
    }
}
$test = &new GroupTest(sprintf('All tests for application %s.', $app));
foreach ($files as $t) {
    if (! function_exists('apc_store') && 'Pluf_Tests_Cache_Apc' === $t[1]) {
        continue;
    }
    $test->addTestCase(new $t[1]());
}
$reporter = new TextReporter();
$mig = new Pluf_Migration(null);
$mig->display = false;
$mig->install();
// If available, load an initialisation file.
if (file_exists($app . '/Tests/init.json')) {
    $created = Pluf_Test_Fixture::loadFile($app . '/Tests/init.json');
} else {
    $created = array();
}
$test->run($reporter);
$mig->unInstall();