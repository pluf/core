<?php
Pluf::loadFunction('SaaSBank_Shortcuts_GetEngineOr404');

/**
 * 
 * 
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *
 */
class SaaSBank_Views_Receipt {
	
	/**
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function find($request, $match) {
// 		$params = array ();
// 		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
	}
	
	/**
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function create($request, $match) {
// 		$params = array ();
// 		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
        $engine = SaaSBank_Shortcuts_GetEngineOr404('zarinpal');
        $engine->create();
	}
	
	/**
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function get($request, $match) {
// 		$params = array ();
// 		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
	}
	
	/**
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function update($request, $match) {
// 		$params = array ();
// 		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
	}
	
	/**
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function delete($request, $match) {
// 		$params = array ();
// 		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
	}
}
