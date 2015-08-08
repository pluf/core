'use strict';

/**
 * مدیریت نرم‌افزارها را انجام می‌دهد
 */
app.factory('SaaSManager', function($rootScope, $http, $q, $window,
		PaginatorPage, PaginatorParameter, Application) {
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
		_loadDefault : function(deferred) {
			var scope = this;
			$http.get('/api/saas/app').success(function(appData) {
				if (appData == null) {
					deferred.reject();
					return;
				}
				var app = scope._retrieveInstance(appData.id, appData);
				deferred.resolve(app);
				scope._default = app.id;
				$rootScope.$broadcast('SaaSManager.Default.Changed', app);
			}).error(function() {
				deferred.reject();
			});
		},
		_loadMembers : function($app, deferred) {
			$http.get('/api/saas/app/' + $app.id + '/member/list').success(
					function(members) {
						$app.members = members;
						deferred.resolve($app.members);
					}).error(function() {
				deferred.reject();
			});
		},
		_paginate : function($params, $deferred) {
			var scope = this;
			$http({
				method : 'GET',
				url : '/api/saas/app/list',
				params : $params.getParameter(),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var page = new PaginatorPage(res.data);
				$deferred.resolve(page);
			}, function(res) {
				$deferred.reject(res.data);
			});
		},
		_updateApplication : function(app, key, value, deferred) {
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
			}, function(res) {
				alert(res);
				deferred.reject();
			});
		},
		_create : function(title, description, deferred) {
			var scope = this;
			return $http({
				method : 'POST',
				url : '/api/saas/app',
				data : $.param({
					'title' : title,
					'description' :description,
				}),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var data = res.data;
				var message = scope._retrieveInstance(data.id, data);
				deferred.resolve(message);
			}, function(res) {
				alert(res);
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
		 * معمولا یک نرم‌افزار به عنوان نرم‌افزار پیش فرض در سیستم به کار گرفته
		 * می‌شود. این فراخوانی نرم‌افزار پیش فرض را تعیین می‌کند.
		 */
		defaultApplication : function() {
			var deferred = $q.defer();
			if (this._default === 0) {
				this._loadDefault(deferred);
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
		getMembers : function($application) {
			var deferred = $q.defer();
			if ($application.members) {
				deferred.resolve($application.members);
			} else {
				this._loadMembers($application, deferred);
			}
			return deferred.promise;
		},
		search : function($size, $page, $query) {
			var $pag = new PaginatorParameter();
			$pag.setSize($size).setPage($page).setQuery($query);
			return this.paginate($pag);
		},
		paginate : function($params) {
			var scope = this;
			var deferred = $q.defer();
			this._paginate($params, deferred);
			return deferred.promise;
		},
		updateApplication : function(app, key, value) {
			var deferred = $q.defer();
			this._updateApplication(app, key, value, deferred);
			return deferred.promise;
		},
		create : function(title, description) {
			var deferred = $q.defer();
			this._create(title, description, deferred);
			return deferred.promise;
		}
	};
	return manager;
});

/**
 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
 */
app.factory('Application', function($rootScope) {
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
