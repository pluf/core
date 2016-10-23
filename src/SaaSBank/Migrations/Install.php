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
Pluf::loadFunction ( 'Pluf_Shortcuts_LoadModels' );
Pluf::loadFunction ( 'Pluf_Shortcuts_LoadPermissions' );

// TODO: maoso, replse with default installation
function SaaSBank_Migrations_Install_setup($params = null) {
	$filename = dirname ( __FILE__ ) . '/../module.json';
	$myfile = fopen ( $filename, "r" ) or die ( "Unable to open module.json!" );
	$json = fread ( $myfile, filesize ( $filename ) );
	fclose ( $myfile );
	$moduel = json_decode ( $json, true );
	
	Pluf_Shortcuts_LoadModels ( $moduel );
	// Pluf_Shortcuts_LoadPermissions($moduel);
}

