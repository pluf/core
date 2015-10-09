'use strict';

angular.module('pluf.help', ['pluf'])

/**
 * مدیریت صفحه‌های ویکی را ایجاد می‌کند این مدیریت قادر است یک صفحه ویکی را در
 * اختیار کاربران قرار دهد.
 */
.service('$help',
        function($http, $httpParamSerializerJQLike, $q, PException, WikiPage) {
          this._pool = {}
          this._get = function(id) {
            return this._pool[id];
          }
          this._ret = function(id, data) {
            var instance = this._pool[id];
            if (instance) {
              instance.setData(data);
            } else {
              instance = new WikiPage(data);
              this._pool[id] = instance;
            }
            return instance;
          }
          /* فراخوانی‌های عمومی */
          /**
           * صفحه معادل با شناسه را تعیین می‌کند.
           * 
           * @param i
           *          شناسه صفحه
           * @return promise برای اجرا
           */
          this.get = function(i) {
            return this.page({
              id: i,
              languate: 'fa'
            });
          }

          /**
           * صفحه مورد نظر را لود می‌کند.
           */
          this.page = function($p) {
            var p = this._get($p.id);
            if (p) {
              var d = $q.defer();
              d.resolve(p);
              return d.promise;
            }
            if (!('language' in $p)) {
              $p.language = 'fa';
            }
            var scope = this;
            return $http({
              method: 'GET',
              url: '/api/wiki/' + $p.language + '/' + $p.id,
            }).then(function(res) {
              var m = scope._ret(res.data.id, res.data);
              return m;
            }, function(res) {
              throw new PException(res.data);
            });
          }
        })

/**
 * فیلتر نمایش صفحه‌ها را ایجاد می‌کند.
 */
.filter('unsafe', function($sce) {
  return function(val) {
    return $sce.trustAsHtml(val);
  };
})

/**
 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
 */
.factory('WikiPage', function() {
  var wikiPage = function(d) {
    if (d) {
      this.setData(d);
    }
  };
  wikiPage.prototype = {
    setData: function(d) {
      this.data = d;
    },
    isAvailable: function() {
      if (this.id && this.id > 0) { return true; }
      return false;
    },
    render: function() {
      if (this.data.content) { return markdown.toHTML(this.data.content); }
    }
  };
  return wikiPage;
});
