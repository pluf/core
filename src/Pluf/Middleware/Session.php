<?php

/**
 * میان افزار نشست
 *
 * 
 * Allow a session object in the request.
 */
class Pluf_Middleware_Session
{

    /**
     * Process the request.
     *
     * FIXME: We should logout everybody when the session table is emptied.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request (&$request)
    {
        $session = new Pluf_Session();
        if (! isset($request->COOKIE[$session->cookie_name])) {
            // No session is defined. We set empty session.
            $request->session = $session;
            if (isset($request->COOKIE[$request->session->test_cookie_name])) {
                $request->session->test_cookie = $request->COOKIE[$request->session->test_cookie_name];
            }
            return false;
        }
        try {
            $data = self::_decodeData($request->COOKIE[$session->cookie_name]);
        } catch (Exception $e) {
            $request->session = $session;
            if (isset($request->COOKIE[$request->session->test_cookie_name])) {
                $request->session->test_cookie = $request->COOKIE[$request->session->test_cookie_name];
            }
            return false;
        }
        if (isset($data['Pluf_Session_key'])) {
            $sql = new Pluf_SQL('session_key=%s', $data['Pluf_Session_key']);
            $found_session = Pluf::factory('Pluf_Session')->getList(
                    array(
                            'filter' => $sql->gen()
                    ));
            if (isset($found_session[0])) {
                $request->session = $found_session[0];
            } else {
                $request->session = $session;
            }
        } else {
            $request->session = $session;
        }
//         if ($set_lang and
//                  false == $request->session->getData('pluf_language', false)) {
//             $request->session->setData('pluf_language', $set_lang);
//         }
        if (isset($request->COOKIE[$request->session->test_cookie_name])) {
            $request->session->test_cookie = $request->COOKIE[$request->session->test_cookie_name];
        }
        return false;
    }

    /**
     * Process the response of a view.
     *
     * If the session has been modified save it into the database.
     * Add the session cookie to the response.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @param
     *            Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response ($request, $response)
    {
        if ($request->session->touched) {
            if ($request->session->id > 0) {
                $request->session->update();
            } else {
                $request->session->create();
            }
            $data = array();
            $data['Pluf_Session_key'] = $request->session->session_key;
            $response->cookies[$request->session->cookie_name] = self::_encodeData(
                    $data);
        }
        if ($request->session->set_test_cookie != false) {
            $response->cookies[$request->session->test_cookie_name] = $request->session->test_cookie_value;
        }
        return $response;
    }

    /**
     * Encode the cookie data and create a check with the secret key.
     *
     * @param
     *            mixed Data to encode
     * @return string Encoded data ready for the cookie
     */
    public static function _encodeData ($data)
    {
        if ('' == ($key = Pluf::f('secret_key'))) {
            throw new Exception(
                    'Security error: "secret_key" is not set in the configuration file.');
        }
        $data = serialize($data);
        return base64_encode($data) . md5(base64_encode($data) . $key);
    }

    /**
     * Decode the data and check that the data have not been tampered.
     *
     * If the data have been tampered an exception is raised.
     *
     * @param
     *            string Encoded data
     * @return mixed Decoded data
     */
    public static function _decodeData ($encoded_data)
    {
        $check = substr($encoded_data, - 32);
        $base64_data = substr($encoded_data, 0, strlen($encoded_data) - 32);
        if (md5($base64_data . Pluf::f('secret_key')) != $check) {
            throw new Exception('The session data may have been tampered.');
        }
        return unserialize(base64_decode($base64_data));
    }

    public static function processContext ($signal, &$params)
    {
        $params['context'] = array_merge($params['context'], 
                Pluf_Middleware_Session_ContextPreProcessor($params['request']));
    }
}


Pluf_Signal::connect('Pluf_Template_Context_Request::construct', 
        array(
                'Pluf_Middleware_Session',
                'processContext'
        ));