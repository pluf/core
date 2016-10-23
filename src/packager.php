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

class DirectoryTreeIterator extends RecursiveIteratorIterator
{
    function __construct($path)
    {
        parent::__construct(
           new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::KEY_AS_FILENAME), 
           parent::SELF_FIRST);
    }
}

$patterns = array();
if (file_exists('./packager.ignore')) {
    $patterns = file('./packager.ignore', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo('Excluding patterns:'."\n");
    foreach ($patterns as $p) {
        echo(' '.$p."\n");
    }
}

$output = array();
$size = 0;
if ($argc != 3) {
    $output_file = 'Pluf.pkg.php';
} else {
    $output_file = $argv[2];
}
unlink($output_file);
foreach(new DirectoryTreeIterator($argv[1]) as $file) {
    $skip = false;
    foreach ($patterns as $p) {
        if (false !== strpos($file->getPathname(), $p)) {
            $skip = true;
            break;
        }
    }
    if ($skip) continue;
    if ($file->isDir()) {
        echo 'Get files in: '.$file->getPathname()."\n";
    } else {
        $pathinfo = pathinfo($file->getPathname());
        if ($pathinfo['extension'] == 'php') {
            $files[] = $file->getPathname();
            $size += $file->getSize();
        }
    }
}
sort($files);
foreach ($files as $file) {
    echo '.';
    $return = 0;
    $tmp_out = array();
    exec('php -w '.escapeshellarg($file), $tmp_out, $return);
    if ($return != 0) {
        die('error');
    }
    if ($tmp_out[0] == '<?php') {
        array_shift($tmp_out);
    }
    $end = end($tmp_out);
    if (substr($end, -2) == '?>') {
        echo('>> '.$file."\n");
    }
    $output = array_merge($output, $tmp_out);
}
echo "\n";
echo 'Compiled files: '.count($files)."\n";
file_put_contents($output_file, '<?php '.implode("\n", $output).' ?>');
echo('Final size: '.(int)(100*filesize($output_file)/$size).'%'."\n");
?>