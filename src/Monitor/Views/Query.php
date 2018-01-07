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

class Monitor_Views_Query
{

    /**
     * Queries monitor data
     * 
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response
     */
    public function query ($request, $match)
    {
        return self::redirect('/api/v1/query', $request);
    }
    
    /**
     * 
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response
     */
    public function queryRange ($request, $match)
    {
        return self::redirect('/api/v1/query_range', $request);
    }
    
    /**
     * 
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response
     */
    public function series ($request, $match)
    {
        return self::redirect('/api/v1/series', $request);
    }

    /**
     * Redirect input request
     * 
     * @param string $path
     * @param Pluf_HTTP_Request $request
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response
     */
    public static function redirect($path, $request){
        // XXX: maso, 2017: load backend monitor server
//         $path = Tenant_Service::get('monitor.prometheus.server', 'http://localhost:9090');
//         $client = new \GuzzleHttp\Client(
//             array(
//                 'base_uri' => $path
//             ));
//         $res = $client->request($request->method, '/api/v1/query' ,
//             array(
//                 'stream' => false,
//                 'debug' => false,
//                 'headers' => $request->HEADERS,
//                 'query' => $request->REQUEST
//             ));
//         if ($res->getStatusCode() != 200) {
            throw new Pluf_Exception('Query service is not ready');
//         }
//         return new Pluf_HTTP_Response($res->getBody());
    }
}