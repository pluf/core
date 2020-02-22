<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
namespace Pluf\Template;

/**
 * A string already escaped to display in a template.
 */
class SafeString
{

    public $value = '';

    function __construct($mixed, $safe = false)
    {
        if (is_object($mixed) and 'Pluf_Template_SafeString' == get_class($mixed)) {
            $this->value = $mixed->value;
        } else {
            $this->value = ($safe) ? $mixed : htmlspecialchars($mixed, ENT_COMPAT, 'UTF-8');
        }
    }

    function __toString()
    {
        return $this->value;
    }

    public static function markSafe($string)
    {
        return new SafeString($string, true);
    }
}