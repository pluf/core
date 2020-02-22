<?php
namespace Pluf\Middleware;

use Pluf\Exception;
use Pluf\Middleware;
use Pluf\Tenant;

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class TenantFromSubDomain implements Middleware
{

    function process_request(&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        try {
            // TODO: hadi, 1398-09: domain should be extracted from parent tenant not from request.
            $domain = $request->http_host;
            // Remove 'www.' if exist
            $domain = preg_replace('/^www\./', '', $domain);
            // Extract subdomain
            $subdomain = TenantFromSubDomain::extract_subdomains($domain);
            // پیدا کردن ملک با زیر دامنه داده شده
            $app = Tenant::bySubDomain($subdomain);
            if ($app) {
                $request->tenant = $app;
                $request->application = $app;
            }
        } catch (Exception $e) {
            // echo $e->getMessage();
        }

        return false;
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
     */
    private static function extract_domain($str)
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
     */
    private static function extract_subdomains($str)
    {
        $dom = TenantFromSubDomain::extract_domain($str);

        $subdomains = rtrim(strstr($str, $dom, true), '.');

        return $subdomains;
    }

    public function process_response($request, $response)
    {}
}
