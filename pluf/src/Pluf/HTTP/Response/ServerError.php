<?php
/**
 * خرجی معادل با خطای داخلی سیستم
 * 
 * در صوورتی که سیستم با خطای داخلی متوقف شود، این خروجی به عنوان نتیجه تولید خواهد
 * شد.
 * 
 * @author maso
 *
 */
class Pluf_HTTP_Response_ServerError extends Pluf_HTTP_Response {
	
	/**
	 * یک نمونه جدید از این شئی ایجاد می‌کند
	 *
	 * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 500 بازیابی شده و به عنوان نتیجه
	 * برگردانده شود.
	 * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده خواهد شد.
	 *
	 * @param unknown $request        	
	 */
	function __construct($exception, $mimetype = null, $request = null) {
		$content = '';
		$admins = Pluf::f ( 'admins', array () );
		if (Pluf::f ( 'rest', false )) {
			$mimetype = Pluf::f ( 'mimetype_json', 'application/json' ) . '; charset=utf-8';
			if (! ($exception instanceof Pluf_Exception)) {
				$exception = new Pluf_HTTP_Error500 ( 'Unknown exception', 5000, $exception );
			}
			$exception->setDeveloperMessage ( '' );
			parent::__construct ( json_encode ( $exception ), $mimetype );
			$this->status_code = $exception->getStatus();
			return;
		}
		if (count ( $admins ) > 0) {
			// Get a nice stack trace and send it by emails.
			$stack = Pluf_HTTP_Response_ServerError_Pretty ( $exception );
			$subject = $exception->getMessage ();
			$subject = substr ( strip_tags ( nl2br ( $subject ) ), 0, 50 ) . '...';
			foreach ( $admins as $admin ) {
				$email = new Pluf_Mail ( $admin [1], $admin [1], $subject );
				$email->addTextMessage ( $stack );
				$email->sendMail ();
			}
		}
		try {
			$tmpl = new Pluf_Template ( '500.html' );
			$params = array (
					'message' => $exception->getMessage () 
			);
			if (is_null ( $request )) {
				$context = new Pluf_Template_Context ( $params );
			} else {
				$params ['query'] = $request->query;
				$context = new Pluf_Template_Context_Request ( $request, $params );
			}
			$content = $tmpl->render ( $context );
			$mimetype = null;
		} catch ( Exception $e ) {
			$mimetype = 'text/plain';
			$content = 'The server encountered an unexpected condition which prevented it from fulfilling your request.' . "\n\n" . 'An email has been sent to the administrators, we will correct this error as soon as possible. Thank you for your comprehension.' . "\n\n" . '500 - Internal Server Error' . $e;
		}
		parent::__construct ( $content, $mimetype );
		$this->status_code = 500;
	}
}
function Pluf_HTTP_Response_ServerError_Pretty($e) {
	$sub = create_function ( '$f', '$loc="";if(isset($f["class"])){
        $loc.=$f["class"].$f["type"];}
        if(isset($f["function"])){$loc.=$f["function"];}
        return $loc;' );
	$parms = create_function ( '$f', '$params=array();if(isset($f["function"])){
        try{if(isset($f["class"])){
        $r=new ReflectionMethod($f["class"]."::".$f["function"]);}
        else{$r=new ReflectionFunction($f["function"]);}
        return $r->getParameters();}catch(Exception $e){}}
        return $params;' );
	$src2lines = create_function ( '$file', '$src=nl2br(highlight_file($file,TRUE));
        return explode("<br />",$src);' );
	$clean = create_function ( '$line', 'return html_entity_decode(str_replace("&nbsp;", " ", $line));' );
	$desc = get_class ( $e ) . " making " . $_SERVER ['REQUEST_METHOD'] . " request to " . $_SERVER ['REQUEST_URI'];
	$out = $desc . "\n";
	if ($e->getCode ()) {
		$out .= $e->getCode () . ' : ';
	}
	$out .= $e->getMessage () . "\n\n";
	$out .= 'PHP: ' . $e->getFile () . ', line ' . $e->getLine () . "\n";
	$out .= 'URI: ' . $_SERVER ['REQUEST_METHOD'] . ' ' . $_SERVER ['REQUEST_URI'] . "\n\n";
	$out .= '** Stacktrace **' . "\n\n";
	$frames = $e->getTrace ();
	foreach ( $frames as $frame_id => $frame ) {
		if (! isset ( $frame ['file'] )) {
			$frame ['file'] = 'No File';
			$frame ['line'] = '0';
		}
		$out .= '* ' . $sub ( $frame ) . '
        [' . $frame ['file'] . ', line ' . $frame ['line'] . '] *' . "\n";
		if (is_readable ( $frame ['file'] )) {
			$out .= '* Src *' . "\n";
			$lines = $src2lines ( $frame ['file'] );
			$start = $frame ['line'] < 5 ? 0 : $frame ['line'] - 5;
			$end = $start + 10;
			$out2 = '';
			$i = 0;
			foreach ( $lines as $k => $line ) {
				if ($k > $end) {
					break;
				}
				$line = trim ( strip_tags ( $line ) );
				if ($k < $start && isset ( $frames [$frame_id + 1] ["function"] ) && preg_match ( '/function( )*' . preg_quote ( $frames [$frame_id + 1] ["function"] ) . '/', $line )) {
					$start = $k;
				}
				if ($k >= $start) {
					if ($k != $frame ['line']) {
						$out2 .= ($start + $i) . ': ' . $clean ( $line ) . "\n";
					} else {
						$out2 .= '>> ' . ($start + $i) . ': ' . $clean ( $line ) . "\n";
					}
					$i ++;
				}
			}
			$out .= $out2;
		} else {
			$out .= 'No src available.';
		}
		$out .= "\n";
	}
	$out .= "\n\n\n\n";
	$out .= '** Request **' . "\n\n";
	
	if (function_exists ( 'apache_request_headers' )) {
		$out .= '* Request (raw) *' . "\n\n";
		$req_headers = apache_request_headers ();
		$out .= 'HEADERS' . "\n";
		if (count ( $req_headers ) > 0) {
			foreach ( $req_headers as $req_h_name => $req_h_val ) {
				$out .= $req_h_name . ': ' . $req_h_val . "\n";
			}
			$out .= "\n";
		} else {
			$out .= 'No headers.' . "\n";
		}
		$req_body = file_get_contents ( 'php://input' );
		if (strlen ( $req_body ) > 0) {
			$out .= 'Body' . "\n";
			$out .= $req_body . "\n";
		}
	}
	$out .= "\n" . '* Request (parsed) *' . "\n\n";
	$superglobals = array (
			'$_GET',
			'$_POST',
			'$_COOKIE',
			'$_SERVER',
			'$_ENV' 
	);
	foreach ( $superglobals as $sglobal ) {
		$sfn = create_function ( '', 'return ' . $sglobal . ';' );
		$out .= $sglobal . "\n";
		if (count ( $sfn () ) > 0) {
			foreach ( $sfn () as $k => $v ) {
				$out .= 'Variable: ' . $k . "\n";
				$out .= 'Value:    ' . print_r ( $v, TRUE ) . "\n";
			}
			$out .= "\n";
		} else {
			$out .= 'No data' . "\n\n";
		}
	}
	if (function_exists ( 'headers_list' )) {
		$out .= "\n\n\n\n";
		$out .= '** Response **' . "\n\n";
		$out .= '* Headers *' . "\n\n";
		$resp_headers = headers_list ();
		if (count ( $resp_headers ) > 0) {
			foreach ( $resp_headers as $resp_h ) {
				$out .= $resp_h . "\n";
			}
			$out .= "\n";
		} else {
			$out .= 'No headers.' . "\n";
		}
	}
	return $out;
}

