(function() {
	'use strict';

	var wikiMadule = angular.module('pluf.wiki', [ 'pluf' ]);

	wikiMadule.factory('WikiManager', function($rootScope, $http, $window, $q,
			WikiPage) {
		var partService = {
			_pool : {},
			_language : 'fa',
			_get : function(id) {
				return this._pool[id];
			},
			_retrieveInstance : function(id, data) {
				var instance = this._pool[id];
				if (instance) {
					instance.setData(data);
				} else {
					instance = new WikiPage(data);
					this._pool[id] = instance;
				}
				return instance;
			},
			_load : function(name, deferred) {
				var scope = this;
				return $http({
					method : 'GET',
					url : '/api/wiki/' + this._language + '/' + name,
				}).then(function(res) {
					var data = res.data;
					var message = scope._retrieveInstance(data.id, data);
					deferred.resolve(message);
				}, function(res) {
					deferred.reject();
				});
			},
			/* فراخوانی‌های عمومی */
			/**
			 * نرم‌افزار پیش فرض را تعیین می‌کند.
			 */
			get : function(name) {
				var deferred = $q.defer();
				var page = this._get(name);
				if (page) {
					deferred.resolve(page);
				} else {
					this._load(name, deferred);
				}
				return deferred.promise;
			},
		}
		return partService;
	});

	/**
	 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
	 */
	wikiMadule.factory('WikiPage', function() {
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
			},
			render : function() {
				if (typeof (this.content) === 'undefined'
						|| this.content == null)
					return;
				return markdown.toHTML(this.content);
			}
		};
		return object;
	});

}());