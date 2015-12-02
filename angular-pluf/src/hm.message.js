/*
 * این پرونده جزئی از پروژه ایپارتمان می‌باشد که به صورت متن باز ارائه شده است.
 * 
 * در این پرونده تمام ابزارهای مورد نیاز در مدیریت پیام‌ها را ایجاد کرده‌ایم.
 */
'use strict';

/**
 * مدیریت نرم‌افزارها را انجام می‌دهد
 */
app
/**
 * 
 */
.factory(
        '$hmmessage',
        function($http, $q, $notify, $tenant, HMMessage, PException,
                PaginatorParameter, PaginatorPage) {
          var hmm = function() {

          };
          hmm.prototype = {
            /**
             * مخزنی از تمام پیام‌ها ایجاد می‌کند
             */
            _pool: {},
            /**
             * از بین تمام پیام‌هایی که تا کنون بارگذاری شده است پیام مورد نظر
             * را یافته و به عنوان نتیجه برمی گرداند.
             */
            _get: function(id) {
              return this._pool[id];
            },
            _rim: function(id, data) {
              var instance = this._pool[id];
              if (instance) {
                instance.setData(data);
              } else {
                instance = new HMMessage(data);
                this._pool[id] = instance;
              }
              return instance;
            },
            /* متدهای عمومی */
            message: function(id) {
              var msg = this._get(id);
              if (msg) {
                var deferred = $q.defer();
                deferred.resolve(msg);
                return deferred.promise;
              }
              var scope = this;
              return $http({
                method: 'GET',
                url: '/api/hm/' + $tenant.id + '/message/' + id
              }).then(function(res) {
                var message = scope._rim(res.data.id, res.data);
                return message
              }, function(res) {
                throw new PException(res.data);
              });
            },
            /**
             * فهرستی از واحدها را تعیین می‌کند.
             */
            list: function($size, $page) {
              var $pag = new PaginatorParameter();
              $pag.setOrder('creation_dtime', 'd');
              return this._search($pag.setSize($size).setPage($page));
            },
            /**
             * جستجو در واحدها را انجام می‌دهد
             */
            search: function($size, $page, $query) {
              var $pag = new PaginatorParameter();
              $pag.setSize($size).setPage($page).setQuery($query);
              $pag.setOrder('creation_dtime', 'd');
              return $http({
                method: 'GET',
                url: '/api/hm/' + $tenant.id + '/message/list',
                params: paginatorParam.getParameter(),
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                }
              }).then(function(res) {
                var page = new PaginatorPage(res.data);
                return page;
              }, function(res) {
                throw new PException(res.data);
              });
            },
            /**
             * پیام را به روز می‌کند
             * 
             * @param msg
             *          پیام مورد نظر
             * @param key
             *          کلید داده‌ای که باید به روز شود
             * @param value
             *          مقداری که باید به روز شود
             * @returns
             */
            update: function(msg, key, value) {
              var scope = this;
              var par = {};
              par[key] = value;
              return $http({
                method: 'POST',
                url: '/api/hm/' + $tenant.id + '/message/' + msg.id,
                data: $.param(par),
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                }
              }).then(function(res) {
                var message = scope._rim(res.data.id, res.data);
                return message;
              }, function(res) {
                throw new PException(res.data);
              });
            },
            /**
             * یک پیام را از سیست حدف می‌کند.
             * 
             * @param msg
             *          پیامی را تعیین می‌کند که باید حذف شود.
             * @return قول اجرای یک عمل
             */
            remove: function(msg) {
              return $http({
                method: 'DELETE',
                url: '/api/hm/' + $tenant.id + '/message/' + msg.id,
              }).then(function(res) {
                return msg;
              }, function(res) {
                throw new PException(res.data);
              });
            },
            /**
             * یک پیام جدید را ایجاد می‌کند: دو پارامتر ورودی برای این کار نیاز
             * است که عبارتند از پیام و عنوان آن.
             */
            create: function(t, b) {
              var scope = this;
              return $http({
                method: 'POST',
                url: '/api/hm/' + $tenant.id + '/message/create',
                data: $.param({
                  'title': t,
                  'message': b,
                }),
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                }
              }).then(function(res) {
                var msg = scope._rim(res.data.id, res.data);
                return msg;
              }, function(res) {
                throw new PException(res.data);
              });
            },
            /**
             * یک پیام را به عنوان بازخورد به سیستم اضافه می‌کند.
             * 
             * @param t
             *          عنوان
             * @param b
             *          بدنه پیام
             * @returns
             */
            feedback: function(t, b) {
              return $http({
                method: 'POST',
                url: '/api/hm/feedback',
                data: $.param({
                  'content': t,
                  'title': b
                }),
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                }
              }).then(function(data) {
                return data.data;
              }, function(data) {
                $notify.debug('fail to send feedback', data.data);
                throw new PException(data);
              });
            }
          };
          return hmm;
        })

/**
 * ساختار داده‌ای یک پیام را ایجاد می‌کند.
 */
.factory('HMMessage', function() {
  var object = function(messageData) {
    if (messageData) {
      this.setData(messageData);
    }
  };
  object.prototype = {
    setData: function(messageData) {
      angular.extend(this, messageData);
    },
    isAvailable: function() {
      if (!this.id && this.id > 0) { return true; }
      return false;
    }
  };
  return object;
});
