'use strict';

/**
 * ساختار داده‌ای برای جستجو را تعیین می‌کند.
 */
app.factory('PaginatorParameter', function() {
	var object = function(paginatorParam) {
		if (paginatorParam) {
			this.setData(paginatorParam);
		}
	};
	object.prototype = {
		param : {},
		setData : function(paginatorParam) {
			angular.extend(param, paginatorParam);
		},
		setSize : function($size) {
			this.param['_px_count'] = $size;
			return this;
		},
		setQuery : function($query) {
			this.param['_px_q'] = $query;
			return this;
		},
		setPage : function($page) {
			this.param['_px_p'] = $page;
			return this;
		},
		setOrder : function($key, $order) {
			this.param['_px_sk'] = $key;
			this.param['_px_so'] = $order;
			return this;
		},
		setFilter : function($key, $value) {
			this.param['_px_fk'] = $key;
			this.param['_px_fv'] = $value;
			return this;
		},
		getParameter : function() {
			return this.param;
		}
	};
	return object;
});

/**
 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
 */
app.factory('PaginatorPage', function() {
	var object = function(paginatorData) {
		if (paginatorData) {
			this.setData(paginatorData);
		}
	};
	object.prototype = {
		list : [],
		setData : function(paginatorData) {
			angular.extend(this, paginatorData);
			this.list = [];
			for (var i = 0; i < paginatorData.items_per_page; i++) {
				if (!(typeof paginatorData[i] === "object"))
					break;
				this.list.push(paginatorData[i]);
			}
		},
	};
	return object;
});

/**
 * مدیریت کاربران
 * 
 * این سرویس تنها ابزارهایی را که برای مدیریت عادی یک کاربر مورد نیاز است ارائه می‌کند. برای 
 * نمونه ورود به سیستم، خروج و یا به روز کردن تنظیم‌های کاربری. مدیریت کاربران در سطح سیستم در
 * سرویس‌های دیگر ارائه می‌شود.
 */
app.factory('UserManager', function($rootScope, $http, $q, $window, User, UserProfile) {
	var manager = {
		_pool : {},
		_current : null,
		_search : function(id) {
			return this._pool[id];
		},
		_retrieveInstance : function(id, data) {
			var instance = this._pool[id];
			if (instance) {
				instance.setData(data);
			} else {
				instance = new User(data);
				this._pool[id] = instance;
			}
			return instance;
		},
		_loadCurrent : function(deferred) {
			var scope = this;
			$http.get('/api/user/account').success(function(data) {
				var user = scope._retrieveInstance(data.login, data);
				deferred.resolve(user);
				scope._current = user;
				$rootScope.$broadcast('UserManager.User.Changed', user);
			}).error(function() {
				deferred.reject();
			});
		},
		_logout : function(deferred) {
			var scope = this;
			$http.get('/api/user/logout').success(function(data) {
				var user = scope._retrieveInstance(data.login, data);
				deferred.resolve(user);
				scope._current = user;
				$rootScope.$broadcast('UserManager.User.Changed', user);
			}).error(function() {
				deferred.reject();
			});
		},
		_login : function($login, $password, deferred) {
			var scope = this;
			return $http({
				method : 'POST',
				url : '/api/user/login',
				data : $.param({
					'login' : $login,
					'password' : $password
				}),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var data = res.data;
				var user = scope._retrieveInstance(data.login, data);
				deferred.resolve(user);
				scope._current = user;
				$rootScope.$broadcast('UserManager.User.Changed', user);
			});
		},
		_update : function(user, key, value, deferred){
			var scope = this;
			var param = {};
			param[key] = value;
			return $http({
				method : 'POST',
				url : '/api/user/account',
				data : $.param(param),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var data = res.data;
				var user = scope._retrieveInstance(data.login, data);
				deferred.resolve(user);
				scope._current = user;
				$rootScope.$broadcast('UserManager.User.Changed', user);
			});
		},
		_signup:function(login, firstName, lastName, email, password, deferred){
			var scope = this;
			return $http({
				method : 'POST',
				url : '/api/user/signup',
				data : $.param({
					'login' : login,
					'first_name':firstName,
					'last_name' : lastName,
					'email': email,
					'password' : password
				}),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var data = res.data;
				var user = scope._retrieveInstance(data.login, data);
				deferred.resolve(user);
			}, function(res){
				deferred.reject();
			});
		},
		_profile: function(deferred) {
			return $http({
				method : 'GET',
				url : '/api/user/profile',
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var profile = new UserProfile(res.data);
				deferred.resolve(profile);
			}, function(res){
				deferred.reject();
			});
		},
		_updateProfile : function(key, value, deferred){
			var scope = this;
			var param = {};
			param[key] = value;
			return $http({
				method : 'POST',
				url : '/api/user/profile',
				data : $.param(param),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var profile = new UserProfile(res.data);
				deferred.resolve(profile);
			}, function(res) {
				deferred.reject();
			});
		},
		/* متدهای عمومی */
		/* یک نرم‌افزار را بر اساس شناسه آن تعیین می‌کند */
		getCurrentUser : function() {
			var deferred = $q.defer();
			if (this._current === null) {
				this._loadCurrent(deferred);
			} else {
				deferred.resolve(this._search(this._current));
			}
			return deferred.promise;
		},
		logout : function() {
			var deferred = $q.defer();
			if (this._current === null || this._current.isAnonymous()) {
				if (this._current == null) {
					this._current = new User();
				}
				deferred.resolve(this._current);
			} else {
				this._logout(deferred);
			}
			return deferred.promise;
		},
		login : function($login, $password) {
			var deferred = $q.defer();
			if (this._current === null || this._current.isAnonymous()) {
				this._login($login, $password, deferred);
			} else {
				deferred.resolve(this._current);
			}
			return deferred.promise;
		},
		update : function(user, key, value) {
			var deferred = $q.defer();
			this._update(user, key, value, deferred);
			return deferred.promise;
		},
		signup : function (login, firstName, lastName, email, password){
			var deferred = $q.defer();
			this._signup(login, firstName, lastName, email, password, deferred);
			return deferred.promise;
		},
		/**
		 * پروفایل کاربر را گرفته و به عنوان نتیجه برمی‌گرداند.
		 */
		getProfile : function () {
			var deferred = $q.defer();
			if(this._current && !this._current.isAnonymous()){
				this._profile(deferred);
			} else {
				deferred.reject();
			}
			return deferred.promise;
		},
		/**
		 * به روز کردن پروفایل کاربری
		 * 
		 * با استفاده از این فراخوانی تنها می‌توان کاربری جاری را ویرایش کرد.
		 */
		updateProfile : function(key, value) {
			var deferred = $q.defer();
			if(this._current && !this._current.isAnonymous()){
				this._updateProfile(key, value, deferred);
			} else {
				deferred.reject();
			}
			return deferred.promise;
		},
	};
	return manager;
});

/**
 * ساختار داده‌ای کاربر را تعیین می‌کند.
 */
app.factory('User', function() {
	var object = function(data) {
		if (data) {
			this.setData(data);
		}
	};
	object.prototype = {
		setData : function(data) {
			angular.extend(this, data);
		},
		isAnonymous : function() {
			return (typeof this.id === 'undefined') || this.id === '';
		}
	};
	return object;
});

/**
 * General user profile
 */
app.factory('UserProfile', function(){
	var object = function(data) {
		if (data) {
			this.setData(data);
		}
	};
	object.prototype = {
		setData : function(data) {
			angular.extend(this, data);
		},
		isAnonymous : function() {
			return (typeof this.id === 'undefined') || this.id === '';
		}
	};
	return object;
});

/**
 * ساختارهای داده‌ای یک گروه را تعیین می‌کند
 * 
 * کاربر می‌تواند در یک یا چند گروه عضو باشد. 
 */
app.factory('Group', function() {
	var object = function(data) {
		if (data) {
			this.setData(data);
		}
	};
	object.prototype = {
		setData : function(data) {
			angular.extend(this, data);
		},
	};
	return object;
});
