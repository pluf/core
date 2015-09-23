'use strict';
/**
 * مدیریت نرم‌افزارها را انجام می‌دهد
 */
angular.module('pluf.saas', ['pluf'])
/**
 * یک نسخه نصب شده از نرم افزار را تعیین می‌کند که شامل دسته از کاربران،
 * تنظیم‌ها، و داده‌ها می‌شود.
 */
.factory('$tenant', function($http, $q, $act, $window, PException, PaginatorPage) {
  var tenantService = {
    data: {},
    setData: function(appData) {
      angular.extend(this.data, appData);
    },
    isAnonymous: function() {
      return (typeof this.id === 'undefined') || this.id === '';
    },
    load: function() {
      var scope = this;
      return $http.get('/api/saas/app').then(function(res) {
        scope.setData(res.data);
        return scope;
      }, function(res) {
        throw new PException(res.data);
      });
    },
    setApp: function(id) {
      var scope = this;
      $http.get('/api/saas/app/' + id).then(function(res) {
        scope.setData(res.data);
        return scope;
      },function(res) {
        throw new PException(res.data);
      });
    },
    /**
     * جستجوی نرم‌افزارهای کاربردی با امکان صفحه بندی. از این راهکار برای فهرست
     * کردن و جستجو استفاده می‌شود که کاربردهای فراوانی دارد.
     */
    search: function($params) {
      var scope = this;
      return $http({
        method: 'GET',
        url: '/api/saas/app/list',
        params: $params.getParameter(),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function(res) {
        var page = new PaginatorPage(res.data);
        return page;
      }, function(data) {
        throw new PException(data);
      });
    },
    /**
     * امکان جستجو روی نرم‌افزارهای کاربردی فراهم می‌کند که کاربر به نوعی مجوز
     * دسترسی به آن را دارد. این مجوزها به صورت یک خصویت اضافه می‌شوند. برای
     * دریافت خصوصیت‌های هر مجوز باید از واسطه‌های تعیین شده در pluf_user
     * استفاده شود.
     */
    list: function($params) {
      var scope = this;
      return $http({
        method: 'GET',
        url: '/api/saas/app/user/list',
        params: $params.getParameter(),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function(res) {
        var page = new PaginatorPage(res.data);
        return page;
      }, function(data) {
        throw new PException(data);
      });
    },

    /**
     * با استفاده از این فراخوانی یکی از خصوصیت‌های یک نرم‌افزار کاربردی به روز
     * می‌شود.
     */
    update: function(app, key, value) {
      var scope = this;
      var par = {};
      par[key] = value;
      return $http({
        method: 'POST',
        url: '/api/saas/app/' + this.data.id,
        data: $.param(par),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function(res) {
        scope.setData(res.data);
        return scope;
      }, function(data) {
        throw new PException(data);
      });
      return deferred.promise;
    },
    /**
     * با استفاده از این فراخوانی یکی نرم افزار کاربردی جدید ایجاد می‌شود.
     */
    create: function(t, d) {
      var scope = this;
      return $http({
        method: 'POST',
        url: '/api/saas/app',
        data: $.param({
          'title': t,
          'description': d,
        }),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function(res) {
        scope.setData(res);
        return scope;
      }, function(data) {
        throw new PException(data);
      });
    },
    /*
     * مدیریت اعضای سیستم
     */
    $member: {
      data: {},
      setData: function(appData) {
        angular.extend(this.data, appData);
      },
      /**
       * اعضای یک نرم‌افزار کاربردی را تعیین می‌کند.
       */
      load: function() {
        if (this.isAnonymous() || this.memberLoaded()) {
          var deferred = $q.defer();
          if (this.isAnonymous())
            deferred.reject('authentication requried');
          else
            deferred.resolve(this.$member.data);
          return deferred.promise;
        }
        var scope = tenantService;
        return $http({
          method: 'GET',
          url: '/api/saas/app/' + $application.id + '/member/list'
        }).then(function(res) {
          scope.$member.setData(res.data);
          return scope.$member;
        }, function(data) {
          $notify.debug('fail to get members', data);
          throw new PException(data);
        });
      },
    },
  };
  /**
   * اضافه کردن دستورها و دستگیره‌ها
   */
  $act.command({
    id: 'pluf.saas.app.goto',
    label: 'application',
    description: 'go to an application page',
    category: 'saas',
  }).commandHandler({
    commandId: 'pluf.saas.app.goto',
    handle: function() {
      if(arguments.length < 1){
        throw new PException('application id is not defined');
      }
      var args = arguments[0];
      // XXX: maso, 1394: باید نرم افزار معادل بازیابی و اجرا شود.
      return $window.location.href = "/"+args;
    }
  });
  return tenantService;
})
/**
 * هر نسخه می‌تواند از یک نوع نرم افزار خاص نصب شده استفاده کند. البته نرم
 * افزارهای باید تنها از خدمات ارائه شده در نسخه نصبی استفاده کنند. هر نرم افزار
 * می‌تواند شامل تنظیم‌های متفاتی باشد.
 */
.factory('$application', function() {
  var appService = function() {
    // بار گزاری سرویس
  };
  appService.prototype = {
    setData: function(applicationData) {
      angular.extend(this, applicationData);
    },
    isAnonymous: function() {
      return (typeof this.id === 'undefined') || this.id === '';
    },
  };
  return service;
});
