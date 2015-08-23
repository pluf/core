(function() {
  'use strict';

  angular.module('pluf', ['pluf.paginator', 'pluf.user', 'pluf.core']);

  /**
   * تمام ماژولهای اساسی سیستم در این ماژول جمع می‌شود. این ماژولها معمولا
   * کاربردهای عمومی دارند و می‌تواند در جاهای متفاوت استفاده شوند.
   */
  var coreModel = angular.module("pluf.core", []);
  /**
   * این ماژول برای کش کردن داده‌ها استفاده می‌شود. حالتی را تصور کنید که در آن
   * داده‌ها از اینترنت دانلود می‌شود. اگر داده‌هایی که دانلود شده را دیگر
   * دانلود نکنیم کار بسیار ساده‌تر خواهد بود.
   */
  coreModel.factory('PCache', function() {
    var object = function(paginatorParam) {
      if (paginatorParam) {
        this.setData(paginatorParam);
      }
    };
    object.prototype = {
      _pool: {},
      _search: function(id) {
        return this._pool[id];
      },
    }
    return object;
  });

  /**
   * یک سیستم ساده است برای اعلام پیام در سیستم. با استفاده از این کلاس می‌توان
   * پیام‌های متفاوتی که در سیستم وجود دارد را به صورت همگانی اعلام کرد.
   */
  coreModel.factory('PNotify', function($rootScope) {
    var object = {
      /*
       * فهرست شنودگرهای
       */
      _info: [],
      _warning: [],
      _debug: [],
      _error: [],
      /*
       * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
       */
      onInfo: function(listener) {
        this._info.push(listener);
        return this;
      },
      /**
       * تمام واسطه‌های تعیین شده برای پیام را فراخوانی کرده و آنها را پیام
       * ورودی آگاه می‌کند.
       */
      info: function() {
        for (var i = 0; i < this._info.length; i++){
          this._info[i].apply(this._info[i], arguments);
        }
      },
      /*
       * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
       */
      onWarning: function(listener) {
        this._warning.push(listener);
        return this;
      },
      /**
       * تمام پیام‌های اخطاری که در سیستم تولید شده است را به سایر شنودگرها
       * ارسال کرده و آنها را از بروز آن آگاه می‌کند.
       */
      warning: function() {
        for (var i = 0; i < this._warning.length; i++){
          this._warning[i].apply(this._info[i], arguments);
        }
      },
      /*
       * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
       */
      onDebug: function(listener) {
        this._debug.push(listener);
        return this;
      },
      /**
       * تمام پیام‌هایی که برای رفع خطا در سیستم تولید می‌شود را برای تمام شنودگرهای
       * اضافه شده ارسال می‌کند.
       */
      debug: function() {
        for (var i = 0; i < this._debug.length; i++){
          this._debug[i].apply(this._info[i], arguments);
        }
      },
      /*
       * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
       */
      onError: function(listener) {
        this._error.push(listener);
        return this;
      },
      /**
       * تمام پیام‌های خطای تولید شده در سیتسم را برای تمام شوندگرهایی خطا صادر کرده
       * و آنها را از آن مطلع می‌کند.
       */
      error: function() {
        for (var i = 0; i < this._error.length; i++){
          this._error[i].apply(this._info[i], arguments);
        }
      },
      /*
       * یک رویداد خاص را در کل فضای نرم افزار انتشار می‌دهد. اولین پارامتر
       * ورودی این تابع به عنوان نام و شناسه در نظر گرفت می‌شود و سایر پارامترها
       * به عنوان پارامترهای ورودی آن.
       */
      broadcast: function() {
        return $rootScope.$broadcast.apply($rootScope, arguments);
      }
    };
    return object;
  });

  var paginatorModeul = angular.module("pluf.paginator", []);

  /**
   * ساختار داده‌ای برای جستجو را تعیین می‌کند.
   */
  paginatorModeul.factory('PaginatorParameter', function() {
    var object = function(paginatorParam) {
      if (paginatorParam) {
        this.setData(paginatorParam);
      }
    };
    object.prototype = {
      param: {},
      setData: function(paginatorParam) {
        angular.extend(param, paginatorParam);
      },
      setSize: function($size) {
        this.param['_px_count'] = $size;
        return this;
      },
      setQuery: function($query) {
        this.param['_px_q'] = $query;
        return this;
      },
      setPage: function($page) {
        this.param['_px_p'] = $page;
        return this;
      },
      setOrder: function($key, $order) {
        this.param['_px_sk'] = $key;
        this.param['_px_so'] = $order;
        return this;
      },
      setFilter: function($key, $value) {
        this.param['_px_fk'] = $key;
        this.param['_px_fv'] = $value;
        return this;
      },
      getParameter: function() {
        return this.param;
      }
    };
    return object;
  });

  /**
   * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
   */
  paginatorModeul.factory('PaginatorPage', function() {
    var object = function(paginatorData) {
      if (paginatorData) {
        this.setData(paginatorData);
      }
    };
    object.prototype = {
      list: [],
      setData: function(paginatorData) {
        angular.extend(this, paginatorData);
        this.list = [];
        for (var i = 0; i < paginatorData.items_per_page; i++) {
          if (!(typeof paginatorData[i] === "object")) break;
          this.list.push(paginatorData[i]);
        }
      },
    };
    return object;
  });

  var userModule = angular.module("pluf.user", []);

  /**
   * مدیریت کاربران این سرویس تنها ابزارهایی را که برای مدیریت عادی یک کاربر
   * مورد نیاز است ارائه می‌کند. برای نمونه ورود به سیستم، خروج و یا به روز کردن
   * تنظیم‌های کاربری. مدیریت کاربران در سطح سیستم در سرویس‌های دیگر ارائه
   * می‌شود.
   */
  userModule.factory('UserManager', function($http, $q, $window, $cacheFactory,
          PNotify, User, UserProfile) {
    var manager = {
      _cache: $cacheFactory('PUserManager'),
      _current: null,
      _retrieveInstance: function(id, data) {
        var instance = this._cache.get(id);
        if (instance) {
          instance.setData(data);
        } else {
          instance = new User(data);
          this._cache.put(id, instance);
        }
        return instance;
      },
      _setCurrent: function(user) {
        this._current = user;
        PNotify.broadcast('UserManager.User.Changed', user);
      },
      /* متدهای عمومی */
      /* یک نرم‌افزار را بر اساس شناسه آن تعیین می‌کند */
      getCurrentUser: function() {
        var deferred = $q.defer();
        if (this._current === null) {
          var scope = this;
          $http.get('/api/user/account').success(function(data) {
            var user = scope._retrieveInstance(data.login, data);
            deferred.resolve(user);
            scope._setCurrent(user);
          }).error(function() {
            deferred.reject();
          });
        } else {
          deferred.resolve(this._search(this._current));
        }
        return deferred.promise;
      },
      /*
       * کاربر جاری را از سیستم خارج می‌کند.
       */
      logout: function() {
        var deferred = $q.defer();
        if (this._current === null || this._current.isAnonymous()) {
          if (this._current == null) {
            this._current = new User();
          }
          deferred.resolve(this._current);
        } else {
          var scope = this;
          $http.get('/api/user/logout').success(function(data) {
            var user = scope._retrieveInstance(data.login, data);
            deferred.resolve(user);
            scope._setCurrent(user);
          }).error(function() {
            deferred.reject();
          });
        }
        return deferred.promise;
      },
      /**
       * ورود کاربر به سیستم را پیاده سازی می‌کند.
       */
      login: function($login, $password) {
        var deferred = $q.defer();
        if (this._current === null || this._current.isAnonymous()) {
          var scope = this;
          return $http({
            method: 'POST',
            url: '/api/user/login',
            data: $.param({
              'login': $login,
              'password': $password
            }),
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            }
          }).then(function(res) {
            var user = scope._retrieveInstance(res.data.login, res.data);
            deferred.resolve(user);
            scope._setCurrent(user);
          });
        } else {
          deferred.resolve(this._current);
        }
        return deferred.promise;
      },
      /**
       * خصوصیت تعیین شده با نام key را بر اساس مقدار جدید تعیین شده به روز
       * می‌کند.
       */
      update: function(user, key, value) {
        var deferred = $q.defer();
        var scope = this;
        var param = {};
        param[key] = value;
        return $http({
          method: 'POST',
          url: '/api/user/account',
          data: $.param(param),
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }) // با به روز شدن خصوصیت
        .then(function(res) {
          var user = scope._retrieveInstance(res.data.login, res.data);
          deferred.resolve(user);
          scope._setCurrent(user);
        }, function(reason) {
          defeered.reject(reason);
        });
        return deferred.promise;
      },
      /**
       * یک کاربر جدید را در سیستم ثبت می‌کند.
       */
      signup: function(login, firstName, lastName, email, password) {
        var deferred = $q.defer();
        var scope = this;
        return $http({
          method: 'POST',
          url: '/api/user/signup',
          data: $.param({
            'login': login,
            'first_name': firstName,
            'last_name': lastName,
            'email': email,
            'password': password
          }),
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }).then(function(res) {
          var data = res.data;
          var user = scope._retrieveInstance(data.login, data);
          deferred.resolve(user);
        }, function(reason) {
          deferred.reject(reason);
        });
        return deferred.promise;
      },
      /**
       * پروفایل کاربر را گرفته و به عنوان نتیجه برمی‌گرداند.
       */
      getProfile: function() {
        var deferred = $q.defer();
        if (this._current && !this._current.isAnonymous()) {
          return $http({
            method: 'GET',
            url: '/api/user/profile',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            }
          }).then(function(res) {
            var profile = new UserProfile(res.data);
            deferred.resolve(profile);
          }, function(res) {
            deferred.reject();
          });
        } else {
          deferred.reject();
        }
        return deferred.promise;
      },
      /**
       * به روز کردن پروفایل کاربری با استفاده از این فراخوانی تنها می‌توان
       * کاربری جاری را ویرایش کرد.
       */
      updateProfile: function(key, value) {
        var deferred = $q.defer();
        if (this._current && !this._current.isAnonymous()) {
          var scope = this;
          var param = {};
          param[key] = value;
          return $http({
            method: 'POST',
            url: '/api/user/profile',
            data: $.param(param),
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            }
          }).then(function(res) {
            var profile = new UserProfile(res.data);
            deferred.resolve(profile);
          }, function(res) {
            deferred.reject();
          });
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
  userModule.factory('User', function() {
    var object = function(data) {
      if (data) {
        this.setData(data);
      }
    };
    object.prototype = {
      setData: function(data) {
        angular.extend(this, data);
      },
      isAnonymous: function() {
        return (typeof this.id === 'undefined') || this.id === '';
      }
    };
    return object;
  });

  /**
   * General user profile
   */
  userModule.factory('UserProfile', function() {
    var object = function(data) {
      if (data) {
        this.setData(data);
      }
    };
    object.prototype = {
      setData: function(data) {
        angular.extend(this, data);
      },
      isAnonymous: function() {
        return (typeof this.id === 'undefined') || this.id === '';
      }
    };
    return object;
  });

  /**
   * ساختارهای داده‌ای یک گروه را تعیین می‌کند کاربر می‌تواند در یک یا چند گروه
   * عضو باشد.
   */
  userModule.factory('Group', function() {
    var object = function(data) {
      if (data) {
        this.setData(data);
      }
    };
    object.prototype = {
      setData: function(data) {
        angular.extend(this, data);
      },
    };
    return object;
  });
}());
