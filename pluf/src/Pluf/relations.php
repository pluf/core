<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 * # ***** BEGIN LICENSE BLOCK *****
 * # This file is part of Plume CMS, a website management application.
 * # Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
 * #
 * # Plume CMS is free software; you can redistribute it and/or modify
 * # it under the terms of the GNU Lesser General Public License as published by
 * # the Free Software Foundation; either version 2.1 of the License, or
 * # (at your option) any later version.
 * #
 * # Plume CMS is distributed in the hope that it will be useful,
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * # GNU Lesser General Public License for more details.
 * #
 * # You should have received a copy of the GNU Lesser General Public License
 * # along with this program; if not, write to the Free Software
 * # Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 * #
 * # ***** END LICENSE BLOCK *****
 */

/**
 * For each model having a 'foreignkey' or a 'manytomany' colum, details
 * must be added here.
 * These details are used to generated the methods
 * to retrieve related models from each model.
 */
$user_model = Pluf::f ( 'pluf_custom_user', 'Pluf_User' );
$group_model = Pluf::f ( 'pluf_custom_group', 'Pluf_Group' );

return array (
		$user_model => array (
				'relate_to_many' => array (
						$group_model,
						'Pluf_Permission' 
				) 
		),
		$group_model => array (
				'relate_to_many' => array (
						'Pluf_Permission' 
				) 
		),
		'Pluf_Message' => array (
				'relate_to' => array (
						$user_model 
				) 
		),
		'Pluf_RowPermission' => array (
				'relate_to' => array (
						'Pluf_Permission' 
				) 
		),
		'Pluf_Search_Occ' => array (
				'relate_to' => array (
						'Pluf_Search_Word' 
				) 
		) 
);
