<?php

/**
 * Debug middleware.
 *
 * Simply display small debug information at the end of the page. It
 * requires the xdebug extension.
 */
class Pluf_Middleware_Debug
{

    /**
     * Process the response of a view.
     *
     * If the status code and content type are allowed, add the debug
     * information. Debug must be set to true in the config file to
     * active it.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @param
     *            Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response ($request, $response)
    {
        if (! Pluf::f('debug', false)) {
            return $response;
        }
        if (! in_array($response->status_code, 
                array(
                        200,
                        201,
                        202,
                        203,
                        204,
                        205,
                        206,
                        404,
                        501
                ))) {
            return $response;
        }
        $ok = false;
        $cts = array(
                'text/html',
                'text/html',
                'application/xhtml+xml'
        );
        foreach ($cts as $ct) {
            if (false !== strripos($response->headers['Content-Type'], $ct)) {
                $ok = true;
                break;
            }
        }
        if ($ok == false) {
            return $response;
        }
        $js = '<script type="text/javascript">
// <!--
    function pxDebugGetElementsByClassName(oElm, strTagName, strClassName){
        // Written by Jonathan Snook, http://www.snook.ca/jon; 
        // Add-ons by Robert Nyman, http://www.robertnyman.com
        var arrElements = (strTagName == "*" && document.all)? document.all :
        oElm.getElementsByTagName(strTagName);
        var arrReturnElements = new Array();
        strClassName = strClassName.replace(/\-/g, "\\-");
        var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
        var oElement;
        for(var i=0; i<arrElements.length; i++){
            oElement = arrElements[i];
            if(oRegExp.test(oElement.className)){
                arrReturnElements.push(oElement);
            }
        }
        return (arrReturnElements)
    }
    function pxDebugHideAll(elems) {
      for (var e = 0; e < elems.length; e++) {
        elems[e].style.display = \'none\';
      }
    }
    function pxDebugToggle() {
      for (var i = 0; i < arguments.length; i++) {
        var e = document.getElementById(arguments[i]);
        if (e) {
          e.style.display = e.style.display == \'none\' ? \'block\' : \'none\';
        }
      }
      return false;
    } 
// -->
  </script>';
        $text = '<pre style="text-align: left;">';
        $text .= 'Peak mem: ' . (int) (memory_get_peak_usage() / 1024) . 'kB' .
                 "\n";
        $text .= 'Exec time: ' .
                 sprintf('%.5f', (microtime(true) - $GLOBALS['_PX_starttime'])) .
                 's' . "\n";
        $included_files = get_included_files();
        sort($included_files);
        $text .= '<a href=\'#\' onclick="return pxDebugToggle(\'debug-included-files\')">';
        $text .= 'Included files #: ' . count($included_files);
        $text .= '</a></pre>' . "\n";
        $text .= $js .
                 '<div id="debug-included-files" class="debug-queries"><pre style="text-align: left;">';
        foreach ($included_files as $filename) {
            $text .= htmlspecialchars($filename) . "\n";
        }
        $text .= '</pre></div>';
        if (isset($GLOBALS['_PX_debug_data']['sql_queries'])) {
            $text .= '<pre style="text-align: left;">';
            $text .= '<a href=\'#\' onclick="return pxDebugToggle(\'debug-queries\')">';
            $text .= 'DB query #: ' .
                     count($GLOBALS['_PX_debug_data']['sql_queries']);
            $text .= '</a>' . "\n\n";
            $text .= '</pre>';
            $text .= '<div id="debug-queries" class="debug-queries"><pre style="text-align: left;">';
            foreach ($GLOBALS['_PX_debug_data']['sql_queries'] as $q) {
                $text .= htmlspecialchars($q) . "\n";
            }
            $text .= '</pre></div>';
        } else {
            $text .= '</pre>';
        }
        $text .= '<script type="text/javascript">
      pxDebugHideAll(pxDebugGetElementsByClassName(document, \'div\', \'debug-queries\'));
            </script>';
        
        $response->content = str_replace('</body>', $text . '</body>', 
                $response->content);
        return $response;
    }
}
