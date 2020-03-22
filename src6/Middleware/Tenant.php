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
namespace Pluf\Middleware;

use Pluf\Exception;
use Pluf\Logger;
use Pluf;
use Pluf_HTTP_Request;
use Pluf_HTTP_Response;
use Pluf_HTTP_Response_Redirect;
use Pluf_Tenant;

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Tenant implements \Pluf\Middleware
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Middleware::process_request()
     */
    public function process_request(Pluf_HTTP_Request &$request)
    {
        /*
         * In single tenant mode we do not check anything
         */
        if (! Pluf::f('multitenant', false)) {
            $request->setTenant(Pluf_Tenant::getCurrent());
            return false;
        }

        /*
         * Try to figure out tenant from domain
         */
        try {
            $domain = $request->http_host;
            $tenant = Pluf_Tenant::byDomain($domain);
            if ($tenant) {
                $request->setTenant($tenant);
                return false;
            }
        } catch (Exception $e) {
            Logger::debug('Fail to get tenant from domain address', $e);
        }

        /*
         * trye to get from subdomain
         */
        try {
            $subdomain = self::extract_subdomains($request->http_host);
            $tenant = Pluf_Tenant::bySubDomain($subdomain);
            if ($tenant) {
                $request->setTenant($tenant);
                return false;
            }
        } catch (Exception $e) {
            Logger::debug('Fail to get tenant from domain address', $e);
        }

        /*
         * Fetchs tenant from header
         */
        try {
            if (array_key_exists('_PX_tenant', $request->HEADERS)) {
                $tenant = new Pluf_Tenant($request->HEADERS['_PX_tenant']);
                if ($tenant) {
                    $request->setTenant($tenant);
                    return false;
                }
            }
        } catch (Exception $e) {
            Logger::debug('Fail to get tenant from header', $e);
        }

        /*
         * If no tenant found, then we redirect to the error url
         */
        $redirectUrl = Pluf::f('tenant_notfound_url', 'https://pluf.ir/wb/blog/page/how-config-notfound-tenant');
        return new Pluf_HTTP_Response_Redirect($redirectUrl, 302);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Middleware::process_response()
     */
    public function process_response(Pluf_HTTP_Request $request, Pluf_HTTP_Response $response): Pluf_HTTP_Response
    {
        return $response;
    }

    /**
     * ****************************************************************************************************
     * Note: hadi, 1395: برای استخراج زیر دامنه یا زیردامنه‌ها از یک آدرس از دو متد زیر استفاده کرده‌ایم.
     * خوبی این روش این است که برای پسوندهای چند بخشی مثل co.ir و مانند آن نیز تا حد قابل قبولی کار می‌کند.
     * البته ایراداتی هم دارد. برای اطاعات بیشتر به پیوند زیر مراجعه شود:
     *
     * http://stackoverflow.com/a/12372310
     *
     * پسوندهای قابل قبول برای دامنه‌های عمومی اینترنتی در پیوند زیر قابل مشاهده است:
     * https://publicsuffix.org/list/
     *
     * *****************************************************************************************************
     */

    /**
     * دامنه اصلی را از رشته داده شده استخراج می‌کند
     *
     * @param string $str
     * @return string
     */
    private static function extract_domain(string $str): string
    {
        $matches = array();
        if (preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}(\.[a-z\.]{2,6})?(:[0-9]+)?)$/i", $str, $matches)) {
            return $matches['domain'];
        } else {
            return $str;
        }
    }

    /**
     * زیر دامنه یا زیردامنه‌ها را از رشته داده شده استخراج می‌کند.
     *
     * @param string $str
     * @return string
     */
    private static function extract_subdomains(string $str): string
    {
        $dom = self::extract_domain($str);

        $subdomains = rtrim(strstr($str, $dom, true), '.');

        return $subdomains;
    }
}
