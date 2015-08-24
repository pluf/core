<?php

/**
 * نگاشت تقاضا به لایه نمایش
 * 
 * در این کلاس تقاضای کاربر پردازش شده و بر اساس تنظیم‌ها به یکی از فراخوانی‌های لایه
 * نمایش نگاشت داده می‌شود.
 * 
 * @author maso
 *
 */
class Pluf_Dispatcher
{

    /**
     * نتیجه فراخوانی کاربر را تعیین می‌کند.
     *
     * با ورود یک درخواست این فراخوانی تعیین می‌کند که کدام لایه نمایش باید
     * فراخوانی
     * شود.
     *
     * @param
     *            string Query string ('')
     */
    public static function dispatch ($query = '')
    {
        try {
            $query = preg_replace('#^(/)+#', '/', '/' . $query);
            $req = new Pluf_HTTP_Request($query);
            $middleware = array();
            foreach (Pluf::f('middleware_classes', array()) as $mw) {
                $middleware[] = new $mw();
            }
            $skip = false;
            foreach ($middleware as $mw) {
                if (method_exists($mw, 'process_request')) {
                    $response = $mw->process_request($req);
                    if ($response !== false) {
                        // $response is a response
                        if (Pluf::f('pluf_runtime_header', false)) {
                            $response->headers['X-Perf-Runtime'] = sprintf(
                                    '%.5f', 
                                    (microtime(true) - $GLOBALS['_PX_starttime']));
                        }
                        $response->render(
                                $req->method != 'HEAD' and
                                         ! defined('IN_UNIT_TESTS'));
                        $skip = true;
                        break;
                    }
                }
            }
            if ($skip === false) {
                $response = self::match($req);
                if (! empty($req->response_vary_on)) {
                    $response->headers['Vary'] = $req->response_vary_on;
                }
                $middleware = array_reverse($middleware);
                foreach ($middleware as $mw) {
                    if (method_exists($mw, 'process_response')) {
                        $response = $mw->process_response($req, $response);
                    }
                }
                if (Pluf::f('pluf_runtime_header', false)) {
                    $response->headers['X-Perf-Runtime'] = sprintf('%.5f', 
                            (microtime(true) - $GLOBALS['_PX_starttime']));
                }
                $response->render(
                        $req->method != 'HEAD' and ! defined('IN_UNIT_TESTS'));
            }
        } catch (Exception $e) {
            $response = new Pluf_HTTP_Response_ServerError($e);
            $response->render(
                    $req->method != 'HEAD' and ! defined('IN_UNIT_TESTS'));
            if (defined('IN_UNIT_TESTS')) {
                throw $e;
            }
        }
        /**
         * [signal]
         *
         * Pluf_Dispatcher::postDispatch
         *
         * [sender]
         *
         * Pluf_Dispatcher
         *
         * [description]
         *
         * This signal is sent after the rendering of a request. This
         * means you cannot affect the response but you can use this
         * hook to do some cleaning.
         *
         * [parameters]
         *
         * array('request' => $request,
         * 'response' => $response)
         */
        $params = array(
                'request' => $req,
                'response' => $response
        );
        Pluf_Signal::send('Pluf_Dispatcher::postDispatch', 'Pluf_Dispatcher', 
                $params);
        return array(
                $req,
                $response
        );
    }

    /**
     * تقاضا را با لایه نمایش انطباق می‌دهد
     *
     * زمانی که تمام میان افزارها روی تقاضا اجرا شد در این فراخوانی رابطه میان
     * تقاضا و لایه نمایش تعیین شده و لایه نمایش مناسب اجرا می‌شود. نتیجه این
     * فراخوانی داده‌ای است که باید برای کاربران ارسال شود.
     *
     * @see Pluf_HTTP_URL_reverse
     *
     * @param
     *            Pluf_HTTP_Request Request object
     * @return Pluf_HTTP_Response Response object
     */
    public static function match ($req, $firstpass = true)
    {
        try {
            $views = $GLOBALS['_PX_views'];
            $to_match = $req->query;
            $n = count($views);
            $i = 0;
            while ($i < $n) {
                $ctl = $views[$i];
                // maso, 1394: بررسی متد لایه کنترل
                if (isset($ctl['http-method'])) {
                    $methods = $ctl['http-method'];
                    if ((! is_array($methods) &&
                             $ctl['http-method'] !== $req->method) || (is_array(
                                    $methods) &&
                             ! in_array($req->method, $methods))) {
                        $i ++;
                        continue;
                    }
                }
                if (preg_match($ctl['regex'], $to_match, $match)) {
                    if (! isset($ctl['sub'])) {
                        return self::send($req, $ctl, $match);
                    } else {
                        // Go in the subtree
                        $views = $ctl['sub'];
                        $i = 0;
                        $n = count($views);
                        $to_match = substr($to_match, strlen($match[0]));
                        continue;
                    }
                }
                $i ++;
            }
        } catch (Pluf_HTTP_Error404 $e) {
            // echo $e;
            // Need to add a 404 error handler
            // something like Pluf::f('404_handler', 'class::method')
        }
        if ($firstpass and substr($req->query, - 1) != '/') {
            $req->query .= '/';
            $res = self::match($req, false);
            if ($res->status_code != 404) {
                Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
                $name = (isset($req->view[0]['name'])) ? $req->view[0]['name'] : $req->view[0]['model'] .
                         '::' . $req->view[0]['method'];
                $url = Pluf_HTTP_URL_urlForView($name, 
                        array_slice($req->view[1], 1));
                return new Pluf_HTTP_Response_Redirect($url, 301);
            }
        }
        throw new Pluf_HTTP_Error404();
    }

    /**
     * نمایشی که در فراخوانی self::match تعیین شده است را فراخوانی می‌کند.
     *
     * فراخوانی لایه نمایش ممکن است که با بروز استثنا روبرو شود که در اینجا این
     * نکته
     * مورد توجه قرار گرفته است. از این رو نیازی نیست که در لایه نمایش مدیریت
     * خطا انجام
     * شود.
     *
     * @param
     *            Pluf_HTTP_Request Current request
     * @param
     *            array The url definition matching the request
     * @param
     *            array The match found by preg_match
     * @return Pluf_HTTP_Response Response object
     */
    public static function send ($req, $ctl, $match)
    {
        $req->view = array(
                $ctl,
                $match,
                'ctrl'=>$ctl,
                'match' => $match
        );
        $m = new $ctl['model']();
        if (isset($m->{$ctl['method'] . '_precond'})) {
            // Here we have preconditions to respects. If the "answer"
            // is true, then ok go ahead, if not then it a response so
            // return it or an exception so let it go.
            $preconds = $m->{$ctl['method'] . '_precond'};
            if (! is_array($preconds)) {
                $preconds = array(
                        $preconds
                );
            }
            foreach ($preconds as $precond) {
                if (! is_array($precond)) {
                    $res = call_user_func_array(explode('::', $precond), 
                            array(
                                    &$req
                            ));
                } else {
                    $res = call_user_func_array(explode('::', $precond[0]), 
                            array_merge(
                                    array(
                                            &$req
                                    ), array_slice($precond, 1)));
                }
                if ($res !== true) {
                    return $res;
                }
            }
        }
        if (! isset($ctl['params'])) {
            return $m->$ctl['method']($req, $match);
        } else {
            return $m->$ctl['method']($req, $match, $ctl['params']);
        }
    }

    /**
     * لایه کنترل را بارگذاری می‌کند.
     *
     * @param
     *            string File including the views.
     * @return bool Success.
     */
    public static function loadControllers ($file)
    {
        if (file_exists($file)) {
            $GLOBALS['_PX_views'] = include $file;
            return true;
        }
        return false;
    }
}

