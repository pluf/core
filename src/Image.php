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

namespace Pluf;

/**
 * Image object to wrap some simple convertion operations.
 *
 * Complex operations should be performed with the gd functions directly.
 */
class Pluf_Image
{

    protected $dir = '';

    /**
     * < Path to the thumbnail folder.
     */
    protected $filename = '';

    /**
     * < Filename of the last created thumbnail.
     */
    public $source = '';

    /**
     * < Full path to the source file.
     */
    
    /**
     * Init the image class.
     */
    public function __construct ($source)
    {
        $this->source = $source;
    }

    /**
     * Convert the image as a png file.
     *
     * @param
     *            string Output file.
     */
    public function asPng ($output)
    {
        $this->saveAs($output, 'png');
    }

    /**
     * Convert the image as a given type.
     *
     * Types are 'gif', 'jpg' or 'png'
     *
     * @param
     *            string Output file.
     * @param
     *            string Type.
     */
    public function saveAs ($output, $type = 'png')
    {
        $types = array(
                'gif' => '1',
                'jpg' => '2',
                'png' => '3'
        );
        $output_func = array(
                'gif' => 'imagegif',
                'jpg' => 'imagejpeg',
                'png' => 'imagepng'
        );
        if (! file_exists($this->source)) {
            throw new Exception(
                    sprintf('Image source "%s" unavailable.', $this->source));
        }
        if (($size = @getimagesize($this->source)) === false) {
            throw new Exception(
                    sprintf('Image source "%s" is not an image.', $this->source));
        }
        $source_type = $size[2];
        if ($source_type == $types[$type]) {
            // Simply the source to the output.
            if (false === @copy($this->source, $output)) {
                throw new Exception(
                        sprintf('Failed to copy %s to %s.', $this->source, 
                                $output));
            }
            return;
        }
        if ($source_type == '1') {
            $function = 'imagecreatefromgif';
        } elseif ($source_type == '2') {
            $function = 'imagecreatefromjpeg';
        } elseif ($source_type == '3') {
            $function = 'imagecreatefrompng';
        } else {
            throw new Exception('The source image is not of a recognized type.');
        }
        if (($img = @$function($this->source)) == false) {
            throw new Exception('Cannot read the source image.');
        }
        if (! @$output_func[$type]($img, $output)) {
            throw new Exception('Cannot write the output image.');
        }
    }
}