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
 * This field will automatically slugify its content.
 *
 * A slug is a short label for something, containing only letters, 
 * numbers, underscores or hyphens. They're generally used in URLs.
 * 
 * In your model, you can specify `max_length` in the `widget_attrs`
 * parameter. If `max_length` is not specified, Pluf will use a 
 * default length of 50.
 */
class Pluf_DB_Field_Slug extends Pluf_DB_Field
{
    public $type = 'varchar';

    /**
     * @see Pluf_DB_Field::formField()
     */
    function formField($def, $form_field = 'Pluf_Form_Field_Slug')
    {
        return parent::formField($def, $form_field);
    }

    /**
     * Return a "URL friendly" version in lowercase.
     * 
     * Define the words separator with the configuration 
     * option <code>slug-separator</code>. Default to <code>-</code>.
     *
     * @param $value string Value to convert
     * @return string The slugify version.
     */
    public static function slugify($value)
    {
        $separator = Pluf::f('slug-separator', '-');
        $value = Pluf_Text_UTF8::romanize(Pluf_Text_UTF8::deaccent($value));
        $value = preg_replace('#[^'.$separator.'\w]#u',
                              $separator,
                              mb_strtolower($value, Pluf::f('encoding', 'UTF-8')));

        // remove redundant
        $value = preg_replace('#'.$separator.'{2,}#u',
                              $separator,
                              trim($value, $separator));

        return $value;
    }

}
