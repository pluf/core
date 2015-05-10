<?php

// We directly load the functions we are often going to use in the
// views. This makes the code cleaner.
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');


class HM_Views_User {

	
	public $userUpdate_index = array('Pluf_Precondition::staffRequired');
	/**
	 * \breif برگه مدیریت کاربران را نمایش می‌دهد
	 * 
	 * این برگه به عنوان نمایش اصلی و اولیه از مدیریت کاربران است که تنها در
	 * لایه الگو محتوای آن تعیین می‌شود. تمام داده هایی که در این لایه نمایش داده
	 * می‌شوند عبارتند از:
	 * 
	 * - userList
	 * 
	 * \note این نمایش تنها از متد GET حمایت می‌کند.
	 * 
	 * @param unknown_type $request
	 * @param unknown_type $match
	 */
	public function index($request, $match) {
		$params=array();
		$params['title']=__("user management");
		
		$user = new Pluf_User();
		$params['ulist'] = $user->getList();
	
		// Render the template and send the response to the user
		return Peechak_Shortcuts_RenderToResponse('peechak/user/management.html', $params, $request);
	}
	
	/**
	 * \brief فهرست تمام کاربران را نمایش می‌دهد
	 * 
	 * این نمایش با استفاده از دو متد زیر قابل فراخوانی است:
	 * 
	 * - GET
	 * - POST
	 * 
	 * در این نمایش داده‌های زیر در نظر گرفته شده است:
	 * 
	 * - userList
	 * 
	 * @param unknown_type $request
	 * @param unknown_type $match
	 */
	public function listUser ($request, $match) {
		$params=array();
		$params['title']=__("user management");
		
		$user = new Pluf_User();
		$params['ulist'] = $user->getList();
	
		// Render the template and send the response to the user
		return Peechak_Shortcuts_RenderToResponse('peechak/user/list.html', $params, $request);
	}
	
	/**
	 * \brief فهرست تمام کاربران فعال را نمایش می‌دهد
	 * 
	 * این نمایش با استفاده از دو متد زیر قابل فراخوانی است:
	 * 
	 * - GET
	 * - POST
	 * 
	 * در این نمایش داده‌های زیر در نظر گرفته شده است:
	 * 
	 * - userList
	 * 
	 * @param unknown_type $request
	 * @param unknown_type $match
	 */
	public function listActiveUser ($request, $match) {
		$params=array();
		$params['title']=__("user management");
		
		$user = new Pluf_User();
		$filter = array();
		$filter[] = 'active';
		$p = array();
		$p['filter']=$filter;
		$params['ulist'] = $user->getList($p);
		
		// Render the template and send the response to the user
		return Peechak_Shortcuts_RenderToResponse('peechak/user/list-active.html', $params, $request);
	}
	
	/**
	 * \brief فهرست تمام کاربران غیر فعال را نمایش می‌دهد
	 * 
	 * این نمایش با استفاده از دو متد زیر قابل فراخوانی است:
	 * 
	 * - GET
	 * - POST
	 * 
	 * در این نمایش داده‌های زیر در نظر گرفته شده است:
	 * 
	 * - userList
	 * 
	 * @param unknown_type $request
	 * @param unknown_type $match
	 */
	public function listUnactiveUser ($request, $match) {
		$params=array();
		$params['title']=__("user management");
		
		$user = new Pluf_User();
		$filter = array();
		$filter[] = 'not active';
		$p = array();
		$p['filter']=$filter;
		$params['ulist'] = $user->getList($p);
	
		// Render the template and send the response to the user
		return Peechak_Shortcuts_RenderToResponse('peechak/user/list-unactive.html', $params, $request);
	}
	
	
	public $userUpdate_add = array('Pluf_Precondition::staffRequired');
	/**
	 * \brief یک کاربر جدید را به سیستم اضافه می‌کند
	 * 
	 * این نمایش با استفاده از دو متد زیر قابل فراخوانی است:
	 * 
	 * - GET
	 * - POST
	 * 
	 * در متد GET تنها امکان‌های لازم برای ایجاد یک کاربر جدید به وجود خواهد آمد در حالی
	 * که در متد POST داده‌های دریافتی تحلیل شده و بر اساس آن یک کاربر جدید ایجاد خواهد
	 * شد.
	 * 
	 * در این نمایش داده‌های زیر در نظر گرفته شده است:
	 * 
	 * - form
	 * 
	 * form حالت کلی یک فرم است که در سکوی Pluf تعریف شده و تمام فیلدهای مورد نیاز را 
	 * برای یک کاربر دارد.
	 * 
	 * @param unknown_type $request
	 * @param unknown_type $match
	 */
	public function add ($request, $match) {
		//initial page data
		$params = array();
		$params['title'] = __('user management');
		
		$extra = array(
			'request' => $request,
		);
		//POST METHOD
		if ($request->method == 'POST') {
			$form = new Peechak_Form_Admin_UserCreate(
				array_merge($request->POST,$request->FILES),
				$extra);
			if ($form->isValid()) {
				$cuser = $form->save();
				$request->user->setMessage(sprintf(__('The user %s has been created.'), (string) $cuser));
				$url = Pluf_HTTP_URL_urlForView('Peechak_Views_User::index');
				return new Pluf_HTTP_Response_Redirect($url);
			}
		} else {
			$form = new Peechak_Form_Admin_UserCreate(null, $extra);
		}
		
		$params['form'] = $form;
		//GET METHOD
		// Render the template and send the response to the user
		return Peechak_Shortcuts_RenderToResponse('peechak/user/new.html', $params, $request);
	}
	
	/**
	 * @brief به روز رسانی یک کاربر
	 *
	 * یک کارمند و یا مدیر می‌تواند اطلاعات کاربران دیگر را تغییر دهد.
	 * 
	 * @note یک کارمند نمی‌تواند اطلاعات کارمندان دیگر را تغییر دهد. یک مدیر نیز
	 * نمی‌تواند اطلاعات مدیران دیگر را تغییر دهد.
	 */
	public function update ($request, $match){
		$params = array();
		$params['state']='clean';
		$params['message']='';
		
		if($request->method == 'POST'){
			$id = $request->POST['id'];
		}elseif ($request->method == 'GET'){
			$id = $request->GET['id'];
		}else{
			//Job not define
			$params['state']   = Peechak_Base::$STATE_BUG;
			$params['message'] = __('user is not define!');
			$clean             = false;
		}
		
		$user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $id);
		$title = sprintf(__('Update %s'), $user->__toString());
		$params['title']=$title;
		
		// Check the rights.
		$url = Pluf_HTTP_URL_urlForView('Peechak_Views_User::index');
		$error = __('You do not have the rights to update this user.');
		if ($user->administrator and $request->user->id != $user->id) {
			$request->user->setMessage($error);
			return new Pluf_HTTP_Response_Redirect($url);
		}
		if ($user->staff) {
			if (!$request->user->administrator and $request->user->id != $user->id) {
				$request->user->setMessage($error);
				return new Pluf_HTTP_Response_Redirect($url);
			}
		}

		if ($request->method == 'POST') {
			$extra = array(
				'user' => $user,
				'request' => $request,
			);
			$form = new Peechak_Form_Admin_UserUpdate(array_merge($request->POST,
					$request->FILES),
					$extra);
			if ($form->isValid()) {
				$form->save();
				$request->user->setMessage(__('The user has been updated.'));
				return new Pluf_HTTP_Response_Redirect($url);
			}
		} else {
			$extra = array(
				'user' => $user,
				'request' => $request,
			);
			$form = new Peechak_Form_Admin_UserUpdate(null, $extra);
		}
		
		$params['form'] = $form;
		$params['cuser']= $user;
		// Render the template and send the response to the user
		return Peechak_Shortcuts_RenderToResponse('peechak/user/update.html', $params, $request);
	}
	
	/**
	 * @breif برگه اصلی هر کاربر
	 * @param unknown_type $request
	 * @param unknown_type $match
	 * @deprecated see Peechak_Views::dashboard($request, $match)
	 */
	public function dashboard($request, $match) {
// 		$view = new Peechak_Views();
// 		return $view->dashboard($request, $match);
	}

	/**
	 * @brief مدیریت اطلاعات پایه کاربران
	 * @param $request
	 * @param $match
	 * @deprecated see Peechak_Views::myAccount($request, $match)
	 */
	public function myAccount($request, $match)	{
		$view = new Peechak_Views();
		return $view->myAccount($request, $match);
	}
	
	/**
	 * @brief مدیریت اطلاعات پایه کاربران
	 * @param $request
	 * @param $match
	 * @deprecated see Peechak_Views::myAccount($request, $match)
	 */
	public function profile($request, $match) {}

}
