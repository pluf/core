(function() {
  'use strict';

  var userAdmin = angular.module('pluf.useradmin', ['pluf']);

  /**
   * مدیریت کاربران این سرویس تنها ابزارهایی را که برای مدیریت عادی یک کاربر
   * مورد نیاز است ارائه می‌کند. برای نمونه ورود به سیستم، خروج و یا به روز کردن
   * تنظیم‌های کاربری. مدیریت کاربران در سطح سیستم در سرویس‌های دیگر ارائه
   * می‌شود.
   */
  userAdmin.factory('AdminUserManager', function($rootScope, $http, $httpParamSerializerJQLike, $q,
          $window, User, PaginatorPage) {
    var manager = {
      _pool: {},
      _get: function(id) {
        return this._pool[id];
      },
      _set: function(id, data) {
        this._pool[id] = data;
      },
      _retrieveInstance: function(id, data) {
        var instance = this._get(id);
        if (instance) {
          instance.setData(data);
        } else {
          instance = new User(data);
          this._set(id, instance);
        }
        return instance;
      },
      /* متدهای عمومی */
      /**
       * جستجو کاربران از بین تمام کاربران موجود کاربر مورد نظر را جستجو می‌کند.
       * این جستحو بر اساس راهکارهای صفحه بندی ارائه شده در Pluf طراحی شده است.
       */
      search: function(paginatorParam) {
        var deferred = $q.defer();
        $http({
          method: 'GET',
          url: '/api/user/user/list',
          params: paginatorParam.getParameter(),
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }).then(function(res) {
          var page = new PaginatorPage(res.data);
          deferred.resolve(page);
        }, function(res) {
          deferred.reject(res.data);
        });
        return deferred.promise;
      },
    };
    return manager;
  });
}());