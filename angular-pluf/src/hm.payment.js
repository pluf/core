'use strict';

app.factory('HMPaymentManager', function($rootScope, $http, $window, $q,
		HMPayment, PaginatorPage, PaginatorParameter) {
	var service = {
		_pool : {},
		_default : 0,
		_application : null,
		_part : null,
		_get : function(id) {
			return this._pool[id];
		},
		_retrieveInstance : function(id, data) {
			var instance = this._pool[id];
			if (instance) {
				instance.setData(data);
			} else {
				instance = new HMPayment(data);
				this._pool[id] = instance;
			}
			return instance;
		},
		_load : function(id, deferred) {
			var scope = this;
			$http.get('/api/hm/payment/'+id).success(function(data) {
				var object = scope._retrieveInstance(data.id, data);
				deferred.resolve(object);
			}).error(function() {
				deferred.reject();
			});
		},
		_update : function(payment, key, value, deferred){
			var scope = this;
			var par = {};
			par[key] = value;
			return $http({
				method : 'POST',
				url : '/api/hm/payment/' + payment.id,
				data : $.param(par),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var data = res.data;
				var payment = scope._retrieveInstance(data.id, data);
				deferred.resolve(payment);
			}, function(res) {
				alert(res);
				deferred.reject();
			});
		},
		_paginate : function($paginatorParameter, $deferred) {
			var scope = this;
			$http({
				method : 'GET',
				url : '/api/hm/' + scope._application.id + '/part/'+scope._part.id+'/payment/list',
				params : $paginatorParameter.getParameter(),
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
		_partPaginate : function($paginatorParameter, $deferred) {
			var scope = this;
			$http({
				method : 'GET',
				url : '/api/hm/' + scope._application.id + '/part/'+scope._part.id+'/payment/list',
				params : $paginatorParameter.getParameter(),
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
		_applicationPaginate : function($paginatorParameter, $deferred) {
			var scope = this;
			$http({
				method : 'GET',
				url : '/api/hm/' + scope._application.id + '/payment/list',
				params : $paginatorParameter.getParameter(),
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
		_applicationAddPayment : function(title, amount, description, deferred){
			var scope = this;
			return $http({
				method : 'POST',
				url : '/api/hm/'+scope._application.id+'/payment',
				data : $.param({
					'title' : title,
					'amount' : amount,
					'description' : description
				}),
				headers : {
					'Content-Type' : 'application/x-www-form-urlencoded'
				}
			}).then(function(res) {
				var data = res.data;
				var result = [];
				for(var i = 0; i < data.length; i++){
					var payment = scope._retrieveInstance(data[i].id, data[i]);
					result.unshift(payment);
				}
				deferred.resolve(result);
			}, function(res) {
				alert(res);
				deferred.reject();
			});
		},
		/* فراخوانی‌های عمومی */
		/**
		 * نرم‌افزار پیش فرض را تعیین می‌کند.
		 */
		get: function ($id){
			var deferred = $q.defer();
			var payment = this._get($id);
			if (payment) {
				deferred.resolve(payment);
			} else {
				this._load($id, deferred);
			}
			return deferred.promise;
		},
		update : function(payment, key, value){
			var deferred = $q.defer();
			this._update(payment, key, value, deferred);
			return deferred.promise;
		},
		doPayment : function(payment){
			var deferred = $q.defer();
			if(!payment.verified)
				this._update(payment, 'verified', true, deferred);
			else
				deferred.resolve(payment);
			return deferred.promise;
		},
		setPart : function($part) {
			if (typeof $part === 'undefined')
				return;
			this._part = $part;
		},
		partList : function($size, $page) {
			var $pag = new PaginatorParameter();
			return this.paginate($pag.setSize($size).setPage($page));
		},
		partSearch : function($size, $page, $query) {
			var $pag = new PaginatorParameter();
			$pag.setSize($size).setPage($page).setQuery($query);
			$pag.setOrder('creation_dtime','d');
			return this.partPaginate($pag);
		},
		partPaginate : function($paginatorParameter){
			var scope = this;
			var deferred = $q.defer();
			if((typeof this._part === 'undefined') || !this._part.isAvailable()){
				deferred.reject();
			} else {
				this._partPaginate($paginatorParameter, deferred);
			}
			return deferred.promise;
		},
		setApplication : function($application){
			if (typeof $application === 'undefined')
				return;
			this._application = $application;
		},
		applicationSearch : function (size, page, query){
			var pag = new PaginatorParameter();
			pag.setSize(size);
			pag.setPage(page);
			pag.setQuery(query);
			pag.setOrder('creation_dtime','d');
			return this.applicationPaginate(pag);
		},
		applicationPaginate : function($paginatorParameter){
			var scope = this;
			var deferred = $q.defer();
			if((typeof this._application === 'undefined') || !this._application.isAvailable()){
				deferred.reject();
			} else {
				this._applicationPaginate($paginatorParameter, deferred);
			}
			return deferred.promise;
		},
		applicationAddPayment : function (title, amount, description){
			var deferred = $q.defer();
			this._applicationAddPayment(title, amount, description, deferred);
			return deferred.promise;
		}
	}
	return service;
});

app.run(function($rootScope, HMPaymentManager) {
	$rootScope.$on('HMPart.Default.Changed', function(event, part) {
		HMPaymentManager.setPart(part);
	});
});

app.run(function($rootScope, HMPaymentManager) {
	$rootScope.$on('SaaSManager.Default.Changed', function(event, application) {
		HMPaymentManager.setApplication(application);
	});
});

app.factory('HMPayment', function($rootScope) {
	var object = function(data) {
		if (data) {
			this.setData(data);
		}
	};
	object.prototype = {
		setData : function(data) {
			angular.extend(this, data);
		},
		isAvailable : function() {
			if (!this.id && this.id > 0) {
				return true;
			}
			return false;
		}
	};
	return object;
});
