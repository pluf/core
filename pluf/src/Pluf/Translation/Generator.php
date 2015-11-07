<?php

/**
 * Extract all the strings from the template folders and create
 * 'template'.php files with the strings to be translated.
 */
class Pluf_Translation_Generator
{

    public $outputdir = '';

    /**
     * Recursive listing of the content of a folder.
     *
     * @credits http://php.net/dir
     *
     * @param
     *            string Path to browse
     * @param
     *            int How deep to browse (-1=unlimited)
     * @param
     *            string Mode "FULL"|"DIRS"|"FILES" ('FULL')
     * @param
     *            sring Pattern to exclude some files ('')
     * @param
     *            int Must not be defined (0)
     * @return array List of files
     */
    public static function list_dir ($path, $maxdepth = -1, $mode = 'FULL', $exclude = '', 
            $d = 0)
    {
        if (substr($path, strlen($path) - 1) != '/') {
            $path .= '/';
        }
        $dirlist = array();
        if ($mode != 'FILES') {
            $dirlist[] = $path;
        }
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..' &&
                         ($exclude == '' or ! preg_match($exclude, $file))) {
                    $file = $path . $file;
                    if (! is_dir($file)) {
                        if ($mode != 'DIRS') {
                            $dirlist[] = $file;
                        }
                    } elseif ($d >= 0 && ($d < $maxdepth || $maxdepth < 0)) {
                        $result = self::list_dir($file . '/', $maxdepth, $mode, 
                                $exclude, $d + 1);
                        $dirlist = array_merge($dirlist, $result);
                    }
                }
            }
            closedir($handle);
        }
        if ($d == 0) {
            natcasesort($dirlist);
        }
        return $dirlist;
    }

    /**
     * Recursive make of a directory.
     *
     * @credits http://php.net/mkdir
     *
     * @param
     *            string Directory to make
     * @param
     *            octal Chmod of the directory (0777)
     * @return bool Success
     */
    public static function rmkdir ($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode))
            return true;
        if (! self::rmkdir(dirname($dir), $mode))
            return false;
        return @mkdir($dir, $mode);
    }

    public static function is_pathrelative ($dir)
    {
        if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
            return (preg_match('/^\w+:/', $dir) <= 0);
        } else {
            return (preg_match('/^\//', $dir) <= 0);
        }
    }

    public static function unifypath ($path)
    {
        if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
            return str_replace('\\', DIRECTORY_SEPARATOR, $path);
        }
        return $path;
    }

    public static function real_path ($path)
    {
        $_path = self::unifypath($path);
        if (self::is_pathrelative($path)) {
            $_curdir = self::unifypath(realpath('.'));
            $_path = $_curdir . $_path;
        }
        $_startPoint = '';
        if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
            list ($_startPoint, $_path) = explode(':', $_path, 2);
            $_startPoint .= ':';
        }
        // From now processing is the same for WIndows and Unix,
        // and hopefully for others.
        $_realparts = array();
        $_parts = explode(DIRECTORY_SEPARATOR, $_path);
        for ($i = 0; $i < count($_parts); $i ++) {
            if (strlen($_parts[$i]) == 0 || $_parts[$i] == '.') {
                continue;
            }
            if ($_parts[$i] == '..') {
                if (count($_realparts) > 0) {
                    array_pop($_realparts);
                }
            } else {
                array_push($_realparts, $_parts[$i]);
            }
        }
        return $_startPoint . DIRECTORY_SEPARATOR .
                 implode(DIRECTORY_SEPARATOR, $_realparts);
    }

    /**
     * Generate the files.
     */
    public function generate ($outputdir)
    {
        $dest_files = array();
        foreach (Pluf::f('template_folders') as $folder) {
            $src_files = self::list_dir($folder, - 1, 'FULL');
            foreach ($src_files as $file) {
                // Build an associative array where the key is the
                // source file and the value is the destination file.
                $dest_files[$file] = str_replace($folder, $outputdir, $file);
            }
        }
        foreach ($dest_files as $src => $dest) {
            if (is_dir($src)) {
                self::rmkdir($dest);
                @chmod($dest, 0775);
                print 'Create folder: ' . $dest . "\n";
                continue;
            }
            self::rmkdir(dirname($dest));
            print 'Parse to: ' . $dest . '.php';
            $compiler = new Pluf_Translation_TemplateExtractor(
                    self::real_path($src), array(
                            ''
                    ), true);
            $content = $compiler->compile();
            $file = fopen($dest . '.php', 'wb');
            fwrite($file, $content);
            fclose($file);
            @chmod($dest . '.php', 0664);
            print ' - Ok' . "\n";
            continue;
        }
    }
}
