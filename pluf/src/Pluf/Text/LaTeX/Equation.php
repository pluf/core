<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Given a shunk of a latex equation code, render it and return a
 * corresponding image file.
 *
 * Based on code by:
 * Benjamin Zeiss
 *  Copyright (C) 2003 Benjamin Zeiss <zeiss@math.uni-goettingen.de>
 *  http://www.mayer.dial.pipex.com/tex.htm
 * Kjell Magne Fauske
 *  http://www.fauskes.net/nb/htmleqII/
 *
 * Example usage:
 *
 * $latex = new Pluf_Text_Latex_Equation();
 * if (true === $latex->render('e = mc^2')) {
 *     $png_file = $latex->output_file;
 *     $png_full_path = $latex->output_path;
 * } else {
 *     $error = $latex->error_code;
 *     $msg = $latex->error_msg;
 * }
 *
 * Note that the class is not using exception to return the errors,
 * that way you can easily grab the error and depending on the error
 * code/message generate a special .png file to display.
 */

class Pluf_Text_Latex_Equation 
{
    public $tmp_dir = '/tmp'; 
    public $output_dir = '/tmp';
    public $encoding = 'utf-8';
    public $fragment = '';
    public $latex_path = '/usr/bin/latex';
    public $dvipng_path = '/usr/bin/dvipng';
    public $resolution = '120';
    public $bg_color = 'ffffff';
    public $debug = false;

    /**
     * Black listing of tags.
     *
     * this most certainly needs to be extended. in the long term it
     * is planned to use a positive list for more security. this is
     * hopefully enough for now. i'd be glad to receive more bad tags
     * !
     */
    public $tags_blacklist = array(
      'include', 'def', 'command', 'loop', 'repeat', 'open', 'toks', 'output',
      'input', 'catcode', 'name', '^^', '\\every', '\\errhelp',
      '\\errorstopmode', '\\scrollmode', '\\nonstopmode', '\\batchmode',
      '\\read', '\\write', 'csname', '\\newhelp', '\\uppercase', '\\lowercase',
      '\\relax', '\\aftergroup', '\\afterassignment', '\\expandafter',
      '\\noexpand', '\\special');
    public $error_code = 0;
    public $error_msg = '';

    /*
    var $_latex_path = "/usr/local/bin/latex";
    var $_dvips_path = "/usr/local/bin/dvips";
    var $_convert_path = "/usr/local/bin/convert";
    var $_identify_path="/usr/local/bin/identify";
    var $_formula_density = 100;   // originally 110
    var $_xsize_limit = 700;
    var $_ysize_limit = 1000;
    var $_string_length_limit = 1000;
    var $_font_size = 10;
    var $_latexclass = "article"; //install extarticle class if you wish to have smaller font sizes
    var $_tmp_filename;
    var $_image_format = "png"; //change to png if you prefer
                                         );
    var $_errorcode = 0;
    var $_errorextra = '';
    */


    /**
     * Initializes the class
     *
     * @param string Output directory (null)
     * @param string Temp directory (null)
     */
    public function __construct($output_dir=null, $tmp_dir=null) 
    {
        if (!is_null($output_dir)) {
            $this->output_dir = $output_dir;
        }
        if (!is_null($tmp_dir)) {
            $this->tmp_dir = $tmp_dir;
        }
    }


    /**
     * Tries to match the LaTeX Formula given as argument against the
     * formula cache. If the picture has not been rendered before, it'll
     * try to render the formula and drop it in the picture cache directory.
     *
     * @param string formula in LaTeX format
     * @returns the webserver based URL to a picture which contains the
     * requested LaTeX formula. If anything fails, the resultvalue is false.
     */
    function getFormulaURL($latex_formula) {
        // circumvent certain security functions of web-software which
        // is pretty pointless right here
        $latex_formula = preg_replace("/&gt;/i", ">", $latex_formula);
        $latex_formula = preg_replace("/&lt;/i", "<", $latex_formula);

        $formula_hash = md5($latex_formula.$this->_font_size);

        $filename = $formula_hash.".".$this->_image_format;
        $full_path_filename = $this->getPicturePath()."/".$filename;

        if (is_file($full_path_filename)) 
                { 
           return $this->getPicturePathHTTPD()."/".$filename;
        } 
                else {
            // security filter: reject too long formulas
            if (strlen($latex_formula) > $this->_string_length_limit) {  
                $this->_errorcode = 1;
                return false;
            }

            // security filter: try to match against LaTeX-Tags Blacklist
            for ($i=0;$i<sizeof($this->_latex_tags_blacklist);$i++) { 
                if (stristr($latex_formula,$this->_latex_tags_blacklist[$i])) {  
                    $this->_errorcode = 2;
                    return false;
                }
            }

            // security checks assume correct formula, let's render it
            if ($this->renderLatex($latex_formula)) { 
                return $this->getPicturePathHTTPD()."/".$filename;
            } else { 
                // uncomment if required
                //$this->_errorcode = 3;
                return false;
            }
        }
    }

    /**
     * Get the Latex preamble. 
     *
     * You can overwrite this function if you want. 
     *
     * @return string Latex preamble.
     */
    function getPreamble()
    {
        return '\documentclass{article}'."\n"
            .'\usepackage{amsmath}'."\n"
            .'\usepackage{amsthm}'."\n"
            .'\usepackage{amssymb}'."\n"
            .'\usepackage{bm}'."\n"
            .'% \newcommand{\mx}[1]{\mathbf{\bm{#1}}} % Matrix command'."\n"
            .'% \newcommand{\vc}[1]{\mathbf{\bm{#1}}} % Vector command'."\n" 
            .'% \newcommand{\T}{\text{T}}                % Transpose'."\n"
            .'\pagestyle{empty}'."\n"
            .'\begin{document}'."\n";
    }

    /**
     * Renders a LaTeX formula by the using the following method:
     *  - write the formula into a wrapped tex-file in a temporary directory
     *    and change to it
     *  - Create a DVI file using latex (tetex)
     *  - Convert DVI file to png or gif using dvipng
     *  - Save the resulting image to the picture cache directory using an
     *    md5 hash as filename. Already rendered formulas can be found directly
     *    this way.
     *
     * @param string LaTeX formula
     * @param bool Render an inline formulat (false)
     * @return true if the picture has been successfully saved to the picture
     *          cache directory
     */
    function render($latex_formula, $inline=false) 
    {
        $this->output_file = '';
        $this->output_path = '';
        $this->error_code = 0;
        $this->error_msg = '';
        $output_file = $this->getOutputFile($latex_formula, $inline);
        if (file_exists($this->output_dir.'/'.$output_file)) {
            $this->output_file = $output_file;
            $this->output_path = $this->output_dir.'/'.$output_file;
            return true;
        }
        if (!$this->isCleanLatex($latex_formula)) {
            // error code and message set by the method
            return false;
        }
        if ($inline) {
            $body = sprintf("$%s$ \n \\newpage \n", $latex_formula);
        } else {
            $body = sprintf("\\[\n%s \n\\] \n \\newpage \n", $latex_formula);
        }
        $latex_document = $this->getPreamble().$body;
        $latex_document .= '\end{document}';

        $current_dir = getcwd();
        chdir($this->tmp_dir);

        // create temporary latex file
        $tmp_filename = md5($latex_formula.rand());
        $fp = fopen($this->tmp_dir.'/'.$tmp_filename.'.tex', 'a+');
        fputs($fp, $latex_document);
        fclose($fp);

        // create temporary dvi file
        $command = $this->latex_path.' --interaction=nonstopmode '.$tmp_filename.'.tex';
        exec($command);
        if (!is_file($tmp_filename.'.dvi')) {
            $this->clean($tmp_filename);
            chdir($current_dir);
            $this->error_code = 4; 
            $this->error_msg = __('Unable to generate the dvi file.');
            return false; 
        }
        // convert dvi file to png using dvipng
        $bg = 'rgb ';
        $_r = sprintf('%01.2f', hexdec(substr($this->bg_color, 0, 2))/255);
        $_g = sprintf('%01.2f', hexdec(substr($this->bg_color, 2, 2))/255);
        $_b = sprintf('%01.2f', hexdec(substr($this->bg_color, 4, 2))/255);

        $command = $this->dvipng_path.' -q -T tight -D '.$this->resolution.' -z 9 -pp -1 -bg "rgb '.$_r.' '.$_g.' '.$_b.'" -bg transparent '
            .'-o %s.png %s.dvi';
        $command = sprintf($command, $tmp_filename, $tmp_filename);
        exec($command);
        if (!is_file($tmp_filename.'.png')) {
            $this->clean($tmp_filename);
            chdir($current_dir);
            $this->error_code = 5; 
            $this->error_msg = __('Unable to generate the png file.');
            return false; 
        }
        $output_file = $this->getOutputFile($latex_formula, $inline);
        $output_path = $this->output_dir.'/'.$output_file;
        if (false == rename($tmp_filename.'.png', $output_path) 
            or !is_file($output_path)) {
            $this->clean($tmp_filename);
            chdir($current_dir);
            $this->error_code = 6; 
            $this->error_msg = __('Unable to move the png file.');
            return false; 
        }
        $this->clean($tmp_filename);
        $this->output_file = $output_file;
        $this->output_path = $output_path;
        chdir($current_dir);
        return true;
    }

    /**
     * Cleans the temporary directory
     */
    public function clean($tmp_filename) 
    {
        if ($this->debug) return;
        $current_dir = getcwd();
        chdir($this->tmp_dir);
        $exts = array('tex', 'aux', 'log', 'dvi', 'png');
        foreach ($exts as $ext) {
            if (file_exists($tmp_filename.'.'.$ext)) {
                @unlink($tmp_filename.'.'.$ext);
            }
        }
        chdir($current_dir);
    }

    /**
     * Check if the latex code is clean.
     *
     * @param string Latex code.
     * @return bool Is Clean.
     */
    public function isCleanLatex($latex)
    {
        foreach ($this->tags_blacklist as $tag) {
            if (false !== stristr($latex, $tag)) {
                $this->error_code = 2;
                $this->error_msg = sprintf(__('The LaTeX tag "%s" is not acceptable.'), $tag);
                return false;
            }
        }
        return true;
    }

    /**
     * Get the output file based on the latex fragment.
     *
     * @param string Latex.
     * @param bool Is the equation an inline equation (false).
     * @param string Output file with extension without directory.
     */
    public function getOutputFile($latex, $inline=false)
    {
        $inline = ($inline) ? 'inline' : 'normal';
        return md5($this->bg_color.'###'.$inline.'###'.$this->resolution.'###'.$latex).'.png';
    }

}
