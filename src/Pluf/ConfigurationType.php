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
 * Configuration type
 * 
 * 
 * @author maso
 *
 */
final class Pluf_ConfigurationType
{

    const SYSTEM = 0;
    const SYSTEM_PRIVATE = 0;//00
    const SYSTEM_PUBLIC = 2; //10

    const APPLICATION = 1;
    const TENANT = 1;
    const TENANT_PRIVATE = 1; // 01
    const TENANT_PUBLIC = 3;  // 11

}