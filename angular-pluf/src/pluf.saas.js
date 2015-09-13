(function() {
	'use strict';
	var saasMadule = angular.module('pluf.saas', [ 'pluf' ]);
	/**
	 * مدیریت نرم‌افزارها را انجام می‌دهد
	 */
	saasMadule.factory('SaaSManager', function($rootScope, $http, $q, $window,
	        PNotify, PException,PaginatorPage, PaginatorParameter, Application) {
		var manager = {
			_pool : {},
			_default : 0,
			_search : function(id) {
				return this._pool[id];
			},
			_retrieveInstance : function(id, appData) {
				var instance = this._pool[id];
				if (instance) {
					instance.setData(appData);
				} else {
					instance = new Application(appData);
					this._pool[id] = instance;
				}
				return instance;
			},
			_load : function(id, deferred) {
				var scope = this;
				$http.get('/api/saas/app/' + id).success(function(appData) {
					var app = scope._retrieveInstance(appData.id, appData);
					deferred.resolve(app);
				}).error(function() {
					deferred.reject();
				});
			},
			/* متدهای عمومی */
			/* یک نرم‌افزار را بر اساس شناسه آن تعیین می‌کند */
			getApplication : function(id) {
				var deferred = $q.defer();
				var app = this._search(id);
				if (app) {
					deferred.resolve(app);
				} else {
					this._load(id, deferred);
				}
				return deferred.promise;
			},
			/*
			 * معمولا یک نرم‌افزار به عنوان نرم‌افزار پیش فرض در سیستم به کار
			 * گرفته می‌شود. این فراخوانی نرم‌افزار پیش فرض را تعیین می‌کند.
			 */
			defaultApplication : function() {
				var deferred = $q.defer();
				if (this._default === 0) {
					var scope = this;
					$http.get('/api/saas/app').success(
							function(appData) {
								if (appData == null) {
									deferred.reject();
									return;
								}
								var app = scope._retrieveInstance(appData.id,
										appData);
								deferred.resolve(app);
								scope._default = app.id;
								$rootScope.$broadcast(
										'SaaSManager.Default.Changed', app);
							}).error(function() {
						deferred.reject();
					},function(data){
            PNotify.debug('fail to get current app', data);
            deferred.reject(data);
            throw new PException(data);
					});
				} else {
					deferred.resolve(this._search(this._default));
				}
				return deferred.promise;
			},
			/**
			 * مسیر جاری را به مسیر نرم افزار انتقال می‌دهد.
			 */
			gotoApplication : function($applicationId) {
				$window.location.href = '/' + $applicationId;
			},
			/**
			 * مسیر جاری را به مسیر نرم‌افزار پیش فرض تغییر می‌دهد.
			 */
			gotoDefaultApplication : function() {
				this.gotoApplication(this._default);
			},
			/**
			 * اعضای یک نرم‌افزار کاربردی را تعیین می‌کند.
			 */
			getMembers : function($application) {
				var deferred = $q.defer();
				if ($application.members) {
					deferred.resolve($application.members);
				} else if($application.id){
					$http.get('/api/saas/app/' + $application.id + '/member/list')
							.success(function(members) {
								$application.members = members;
								deferred.resolve($application.members);
							},function(data){
		            PNotify.debug('fail to get members', data);
		            deferred.reject(data);
		            throw new PException(data);
		          });
				} else {
					// Application is not loaded
					deferred.reject();
				}
				return deferred.promise;
			},
			/**
			 * امکان جستجو را فراهم می‌کند.
			 * 
			 * این روش جستجو قدیمی است و ممکن است که در نسخه‌های بعد حذف شود. به
			 * جای استفاده از این روش از pagenate استفاده کنید.
			 */
			search : function($size, $page, $query) {
				var $pag = new PaginatorParameter();
				$pag.setSize($size).setPage($page).setQuery($query);
				return this.paginate($pag);
			},
			/**
			 * امکان جستجو روی نرم‌افزارهای کاربردی فراهم می‌کند که کاربر به
			 * نوعی مجوز دسترسی به آن را دارد. این مجوزها به صورت یک خصویت اضافه
			 * می‌شوند. برای دریافت خصوصیت‌های هر مجوز باید از واسطه‌های تعیین
			 * شده در pluf_user استفاده شود.
			 */
			searchUserApplication : function($params) {
				var scope = this;
				var deferred = $q.defer();
				$http({
					method : 'GET',
					url : '/api/saas/app/user/list',
					params : $params.getParameter(),
					headers : {
						'Content-Type' : 'application/x-www-form-urlencoded'
					}
				}).then(function(res) {
					var page = new PaginatorPage(res.data);
					deferred.resolve(page);
				},function(data){
          PNotify.debug('fails to get user apps', data);
          deferred.reject(data);
          throw new PException(data);
        });
				return deferred.promise;
			},
			/**
			 * جستجوی نرم‌افزارهای کاربردی با امکان صفحه بندی. از این راهکار
			 * برای فهرست کردن و جستجو استفاده می‌شود که کاربردهای فراوانی دارد.
			 */
			paginate : function($params) {
				var scope = this;
				var deferred = $q.defer();
				$http({
					method : 'GET',
					url : '/api/saas/app/list',
					params : $params.getParameter(),
					headers : {
						'Content-Type' : 'application/x-www-form-urlencoded'
					}
				}).then(function(res) {
					var page = new PaginatorPage(res.data);
					deferred.resolve(page);
				},function(data){
          PNotify.debug('fails to get search apps', data);
          deferred.reject(data);
          throw new PException(data);
        });
				return deferred.promise;
			},
			/**
			 * با استفاده از این فراخوانی یکی از خصوصیت‌های یک نرم‌افزار کاربردی
			 * به روز می‌شود.
			 */
			updateApplication : function(app, key, value) {
				var deferred = $q.defer();
				var scope = this;
				var par = {};
				par[key] = value;
				return $http({
					method : 'POST',
					url : '/api/saas/app/' + app.id,
					data : $.param(par),
					headers : {
						'Content-Type' : 'application/x-www-form-urlencoded'
					}
				}).then(function(res) {
					var data = res.data;
					var message = scope._retrieveInstance(data.id, data);
					deferred.resolve(message);
				},function(data){
          PNotify.debug('fail to update app', data);
          deferred.reject(data);
          throw new PException(data);
        });
				return deferred.promise;
			},
			/**
			 * با استفاده از این فراخوانی یکی نرم افزار کاربردی جدید ایجاد
			 * می‌شود.
			 */
			create : function(title, description) {
				var deferred = $q.defer();
				var scope = this;
				return $http({
					method : 'POST',
					url : '/api/saas/app',
					data : $.param({
						'title' : title,
						'description' : description,
					}),
					headers : {
						'Content-Type' : 'application/x-www-form-urlencoded'
					}
				}).then(function(res) {
					var data = res.data;
					var message = scope._retrieveInstance(data.id, data);
					deferred.resolve(message);
				},function(data){
          PNotify.debug('fails to create app', data);
          deferred.reject(data);
          throw new PException(data);
        });
				return deferred.promise;
			}
		};
		return manager;
	});

	/**
	 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
	 */
	saasMadule.factory('Application', function($rootScope) {
		var object = function(appData) {
			if (appData) {
				this.setData(appData);
			}
		};
		object.prototype = {
			setData : function(applicationData) {
				angular.extend(this, applicationData);
			},
			isAvailable : function() {
				if (this.id && this.id > 0) {
					return true;
				}
				return false;
			}
		};
		return object;
	});
}());
