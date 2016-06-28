<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 *
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class Bank_Views {
	
	/**
	 * فراخوانی و دریافت بازخورد از درگاه بانک ملت
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function mellat($request, $match) {
		$params = array ();
		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
	}
}
