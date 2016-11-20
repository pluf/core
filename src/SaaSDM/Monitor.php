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

/**
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 * @since 0.1.0
 */
Pluf::loadFunction ( 'SaaSDM_Shortcuts_GetAssetOr404' );

class SaaSDM_Monitor
{

	/**
	 * Retruns number and size of all assets in related tenant
	 *
	 * @param unknown_type $request
	 * @param array $match
	 */
	public static function assets_size ($request, $match)
	{
		
		$result = array(
				'interval' => 100000,
				'type' => 'scalar',
				'size' => 0,
				'count' => 0
		);
		
		$assetList = new Pluf_Paginator(new SaaSDM_Asset());
		
		$sql = new Pluf_SQL('tenant=%s', array($request->tenant->id));
		
		$assetList->forced_where = $sql;
		foreach ( $assetList->render_array() as $asset ) {
 			$asset = SaaSDM_Shortcuts_GetAssetOr404 ($asset );
			$result['size'] += $asset->size;
			$result['count'] ++;
		}
		
		return $result;
	}
	
	/**
	 * Retruns number of all links created in this tenant
	 *
	 * @param unknown_type $request
	 * @param array $match
	 */
	public static function link_counts ($request, $match){
		
		$result = array(
				'interval' => 100000,
				'type' => 'scalar',
				'count' => 0
		);
		
		$linkList = new Pluf_Paginator(new SaaSDM_Link());
		
		$sql = new Pluf_SQL('tenant=%s', array($request->tenant->id));
		
		$linkList->forced_where = $sql;
		
		foreach ( $linkList->render_array() as $link){
			$result['count'] ++;
		}
		return $result;
	}
}