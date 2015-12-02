'use strict';
/**
 * امکانات اولیه برای مکان‌یابی را در اختیار کاربران قرار می‌دهد.
 */
angular.module('pluf.jahanjoo', ['pluf'])
/**
 * ابزارهای مورد نیاز برای یک برچسب را ایجاد می‌کند.
 */
.factory('PTag', function(PObject, PException) {
  var pTag = function() {
    pTag.apply(this, arguments);
  };
  pTag.prototype = new PObject();
  return pTag;
})
/**
 * ابزارهای موردنیاز برای تعیین یک رای را ایجاد می‌کند.
 */
.factory('PVote', function(PObject, PException) {
  var pVote = function() {
    PObject.apply(this, arguments);
  };
  pVote.prototype = new PObject();
  return pVote;
})
/**
 * ساختار داده‌ای یک مکان را ایجاد می‌کند. علاوه بر این ابزارهای اولیه مورد نیاز
 * برای دستکاری مکان را نیز در اختیار می‌گذارد
 */
.factory('PLocation', function($http, PObject, PException) {
  /**
   * یک نمونه جدید از این کلاس ایجاد می‌کند.
   */
  var pLocation = function() {
    PObject.apply(this, arguments);
  };
  pLocation.prototype = new PObject();

  /**
   * این مکان را از سیستم حذف می‌کند.
   */
  pLocation.prototype.remove = function() {
    var scope = this;
    return $http({
      method: 'DELETE',
      url: '/api/jayab/location/' + this.id,
    }).then(function(res) {
      scope.id = 0;
      return scope;
    }, function(res) {
      throw new PException('fail to delete the location.', res.data);
    });
  }
  // returns module
  return pLocation;
})
/**
 * فراخوانی‌ها اولیه سیستم، مانند جستجو و فهرست کردن را فراهم می‌کند.
 */
.service(
        '$jlocation',
        function($rootScope, $http, $q, $window, $usr, PLocation,
                PaginatorPage, PException) {
          this._pool = [];
          /**
           * یک نمونه جدید از این کلاس ایجاد کرده و اون رو توی مخزن می‌زاره
           */
          this.ret = function(d) {
            if (d.id in this._pool) {
              var t = this._pool[d.id];
              t.setData(d);
              return t;
            }
            var t = new PLocation(d);
            this._pool[t.id] = t;
            return t;
          }
          /**
           * گرفتن اطلاعات یک مکان
           */
          this.location = function(i) {
            if (i in this._pool) {
              var d = $q.defer();
              d.resolve(this._pool[i]);
              return d.promise;
            }
            var scope = this;
            return $http({
              method: 'GET',
              url: '/api/jayab/location/' + i,
            }).then(function(res) {
              return scope.ret(res.data);
            }, function(res) {
              throw new PException('fail to get locations.', res.data);
            });
          }
          /**
           * فهرستی از تمام مکان‌های اضافه شده در سیستم.
           */
          this.locations = function(p) {
            var scope = this;
            return $http({
              method: 'GET',
              url: '/api/jayab/location/list',
              params: p.getParameter(),
            }).then(function(res) {
              var page = new PaginatorPage(res.data);
              var items = [];
              for (var i = 0; i < page.counts; i++) {
                var t = scope.ret(page.items[i]);
                items.push(t);
              }
              page.items = items;
              return page;
            }, function(res) {
              throw new PException('fail to get locations.', res.data);
            });
          }
          /**
           * یک مکان جدید را در سیستم تعریف می‌کند این مکان باید به صورت زیر
           * ایجاد بشه: { name: title, description: description, latitude: lat,
           * longitude: long, }
           */
          this.add = function(p) {
            var scope = this;
            return $http({
              method: 'POST',
              url: '/api/jayab/location/create',
              params: p,
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            }).then(function(res) {
              return scope.ret(res.data);
            }, function(res) {
              throw new PException('fail to add location.', res.data);
            });
          }
        });
