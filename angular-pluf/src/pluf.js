'use strict';

angular.module('pluf', ['pluf.paginator', 'pluf.user', 'pluf.core']);

/**
 * ساختار داده‌ای مورد نیاز برای تولید خطا و مدیریت آن را ایجاد می‌کند.
 */
angular.module("pluf.core", [])

/**
 * ساختار پایه گزارش خطا در سیستم.
 */
.factory('PException', function() {
  var pexception = function(data) {
    if (data) {
      this.setData(data);
    }
  };
  pexception.prototype = {
    setData: function(data) {
      angular.extend(this, data);
    },
  };
  return pexception;
})

/**
 * مدیریت داده‌های محلی کاربر را انجام می‌دهد. این داده‌ها به صورت محلی در
 * مرورگر ذخیره سازی می‌شوند.‌
 */
.factory('$preference', function() {
  var prefService = function() {
    // بار گزاری سرویس
  };
  prefService.prototype = {};
  return prefService;
})

/**
 * Command Service (cs) سیستم مدیریت دستورها در سیستم را ایجاد می‌کند. دستور و
 * دستگیره از اکلیپس الهام شده است.
 */
.service('$act', function($q, $timeout, PException) {
  this._categories = [];
  this._commands = [];
  /**
   * ارایه‌ای از دستگیره‌ها است که هر دستگیره بر اساس کلید دستور خود دسته بندی
   * شده است.
   */
  this._handlers = [];
  /**
   * دستور با شناسه تعیین شده را بر می‌گرداند.
   */
  this.getCommand = function(id){
    var def = $q.defer();
    var scope = this;
    $timeout(function() {
      for(var i = 0; i < scope._commands.length; i++){
        if(scope._commands[i].id == id){
          def.resolve(scope._commands[i]);
          return;
        }
      }
    }, 1);
    return def.promise;
  }
  /**
   * تمام دستورهایی که در یک دسته قرار دارند را به صورت غیر همزمان تعیین می‌کند.
   */
  this.category = function(key) {
    var def = $q.defer();
    var scope = this;
    $timeout(function() {
      if (!(key in scope._categories)) {
        scope._categories[key] = [];
      }
      def.resolve(scope._categories[key]);
    }, 1);
    return def.promise;
  }

  /**
   * یک دستور جدید را به سیستم اضافه می‌کند
   */
  this.command = function($c) {
    this._commands.push($c);
    if( !('visible' in $c)){
      $c.visible = function(){return true;};
    }
    if( !('enable' in $c)){
      $c.enable = function(){return true;};
    }
    if(!('priority' in $c)){
      $c.priority = 0;
    }
    if ($c.category) {
      if (!($c.category in this._categories)) {
        this._categories[$c.category] = [];
      }
      this._categories[$c.category].push($c);
    }
    if ($c.categories) {
      for (var i = 0; i < $c.categories.length; ++i) {
        if (!($c.categories[i] in this._categories)) {
          this._categories[$c.categories[i]] = [];
        }
        this._categories[$c.categories[i]].push($c);
      }
    }
    return this;
  }

  /**
   * اضافه کردن دستگیره.
   */
  this.commandHandler = function($ch) {
    if (!($ch.commandId in this._handlers)) {
      this._handlers[$ch.commandId] = [];
    }
    this._handlers[$ch.commandId].push($ch);
    return this;
  }

  /**
   * اجرای یک دستور
   */
  this.execute = function($ci) {
    var def = $q.defer();
    var scope = this;
    $timeout(function() {
      if (!($ci in scope._handlers)) {
        def.reject(new PException({
          message: 'command not found :' + $ci,
          statuse: 400,
          code: 4404
        }));
        return;
      }
      for (var i = 0; i in scope._handlers[$ci]; i++) {
        var handler = scope._handlers[$ci][i];
        handler['handle'].apply(handler['handle'], arguments);
      }
    }, 1);
    return def.promise;
  }
})
/**
 * مدیریت منوها را ایجاد می‌کند
 */
.service('$menu', function($q, $timeout, $act){
  this._menus = [];
  this.menu = function(id){
    var def = $q.defer();
    var scope = this;
    $timeout(function() {
      if (!(id in scope._menus)) {
        scope._menus[id] = [];
      }
      def.resolve(scope._menus[id]);
    }, 1);
    return def.promise;
  }
  this.add = function (id, menu){
    if(!(id in this._menus)){
      this._menus[id] = [];
    }
    if('command' in menu){
      var scope = this;
      $act.getCommand(menu.command).then(function(command){
        if(!('active' in menu)){
        menu.active = function(){
          return $act.execute(menu.command);
        }
      }
      if( !('visible' in menu)){
        menu.visible = function(){
          return command.visible();
        }
      }
      if( !('enable' in menu)){
        menu.enable = function(){
          return command.enable;
        }
      }
      if(!('label' in menu) && ('label' in command)){
        menu.label = command.label;
      }
      if(!('priority' in menu)){
        menu.priority = command.priority;
      }
      // XXX: maso, 1394: خصوصیت‌های دیگر اضافه شود.
    scope._menus[id].push(menu);
      });
       }
          return this;
  }
})
/**
 * یک سیستم ساده است برای اعلام پیام در سیستم. با استفاده از این کلاس می‌توان
 * پیام‌های متفاوتی که در سیستم وجود دارد را به صورت همگانی اعلام کرد.
 */
.factory('$notify', function($rootScope, $timeout, $q) {
  var notifyService = {
    /*
     * فهرست شنودگرهای
     */
    _info: [],
    _warning: [],
    _debug: [],
    _error: [],
    _fire: function(list, args) {
      var deferred = $q.defer();
      $timeout(function() {
        for (var i = 0; i < list.length; i++) {
          list[i].apply(list[i], args);
        }
        deferred.resolve();
      }, 10);
      return deferred.promise;
    },
    /*
     * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
     */
    onInfo: function(listener) {
      this._info.push(listener);
      return this;
    },
    /**
     * تمام واسطه‌های تعیین شده برای پیام را فراخوانی کرده و آنها را پیام ورودی
     * آگاه می‌کند.
     */
    info: function() {
      return this._fire(this._info, arguments);
    },
    /*
     * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
     */
    onWarning: function(listener) {
      this._warning.push(listener);
      return this;
    },
    /**
     * تمام پیام‌های اخطاری که در سیستم تولید شده است را به سایر شنودگرها ارسال
     * کرده و آنها را از بروز آن آگاه می‌کند.
     */
    warning: function() {
      return this._fire(this._warning, arguments);
    },
    /*
     * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
     */
    onDebug: function(listener) {
      this._debug.push(listener);
      return this;
    },
    /**
     * تمام پیام‌هایی که برای رفع خطا در سیستم تولید می‌شود را برای تمام
     * شنودگرهای اضافه شده ارسال می‌کند.
     */
    debug: function() {
      return this._fire(this._debug, arguments);
    },
    /*
     * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
     */
    onError: function(listener) {
      this._error.push(listener);
      return this;
    },
    /**
     * تمام پیام‌های خطای تولید شده در سیتسم را برای تمام شوندگرهایی خطا صادر
     * کرده و آنها را از آن مطلع می‌کند.
     */
    error: function() {
      return this._fire(this._error, arguments);
    },
    /*
     * یک رویداد خاص را در کل فضای نرم افزار انتشار می‌دهد. اولین پارامتر ورودی
     * این تابع به عنوان نام و شناسه در نظر گرفت می‌شود و سایر پارامترها به
     * عنوان پارامترهای ورودی آن.
     */
    broadcast: function() {
      return $rootScope.$broadcast.apply($rootScope, arguments);
    }
  };
  return notifyService;
});

/**
 * ساختار داده‌ای برای جستجو را تعیین می‌کند.
 */
angular.module("pluf.paginator", []).factory('PaginatorParameter', function() {
  var pagParam = function(paginatorParam) {
    if (paginatorParam) {
      this.setData(paginatorParam);
    } else {
      this.setData({});
    }
  };
  pagParam.prototype = {
    param: {},
    setData: function(paginatorParam) {
      // angular.extend(this.param, paginatorParam);
      this.param = paginatorParam;
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
  return pagParam;
})
/**
 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
 */
.factory('PaginatorPage', function() {
  var pagPage = function(pd) {
    if (pd) {
      this.setData(pd);
    }
  };
  pagPage.prototype = {
    list: [],
    setData: function(pd) {
      angular.extend(this, pd);
      this.list = [];
      for (var i = 0; i < pd.items_per_page; i++) {
        if (!(typeof pd[i] === "object")) break;
        this.list.push(pd[i]);
      }
    },
  };
  return pagPage;
});

/**
 * مدیریت کاربر: این سرویس تنها ابزارهایی را که برای مدیریت عادی یک کاربر مورد
 * نیاز است ارائه می‌کند. برای نمونه ورود به سیستم، خروج و یا به روز کردن
 * تنظیم‌های کاربری. مدیریت کاربران در سطح سیستم در سرویس‌های دیگر ارائه می‌شود.
 */
angular.module("pluf.user", []).factory('$usr', function($http, $q) {
  /**
   * یک نمونه جدید از این کلاس ایجاد می‌کند.
   */
  var userService = {
    data: {},
    /**
     * داده‌های کلاس را تعیین می‌کند
     */
    setData: function(data) {
      angular.extend(this.data, data);
    },
    /**
     * ورود کاربر به سیستم
     */
    login: function($login, $password) {
      if (!this.isAnonymous()) {
        var deferred = $q.defer();
        deferred.resolve(this);
        return deferred.promise;
      }
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
      }).then(function(data) {
        scope.setData(data.data);
        return scope;
      }, function(data) {
        throw new PException(data);
      });
    },
    /**
     * کاربری که در نشست تعیین شده است را بازیابی می‌کند.
     * 
     * @returns
     */
    session: function() {
      var scope = this;
      return $http.get('/api/user/account').then(function(data) {
        scope.setData(data.data);
        return scope.data;
      }, function(data) {
        throw new PException(data);
      });
    },
    /**
     * خروج از سیستم
     */
    logout: function() {
      if (this.isAnonymous()) {
        var deferred = $q.defer();
        deferred.resolve(this);
        return deferred.promise;
      }
      var scope = this;
      return $http.get('/api/user/logout').success(function(data) {
        scope.setData(data);
        return scope;
      }).error(function(data) {
        throw new PException(data);
      });
    },
    /**
     * به روز کردن اطلاعات کاربر
     */
    update: function(key, value) {
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
      }).then(function(data) {
        scope.setData(data.data);
        return scope;
      }, function(data) {
        throw new PException(data);
      });
      return deferred.promise;
    },
    /**
     * ثبت نام یک کاربر جدید
     */
    signup: function(login, firstName, lastName, email, password) {
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
      }).then(function(data) {
        scope.setData(data.data);
        return scope;
      }, function(data) {
        throw new PException(data);
      });
    },
    /**
     * وارد بودن در سیستم را تعیین می‌کند
     */
    isAnonymous: function() {
      return (typeof this.data.id === 'undefined') || this.data.id === '';
    },
    /**
     * مدیریت پروفایل کاربر را ایجاد می‌کند
     */
    $profile: {
      data: {},
      setData: function(data) {
        angular.extend(this.data, data);
      },
      session: function() {
        if (service.isAnonymous()) {
          var deferred = $q.defer();
          deferred.reject();
          return deferred.promise;
        }
        var scope = this;
        return $http({
          method: 'GET',
          url: '/api/user/profile',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }).then(function(data) {
          scope.setData(data);
          return scope;
        }, function(res) {
          throw new PException(res.data);
        });
      },
      /**
       * به روز رسانی پروفایل کاربری
       */
      update: function(key, value) {
        if (service.isAnonymous()) {
          var deferred = $q.defer();
          deferred.reject();
          return deferred.promise;
        }
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
        }).then(function(data) {
          scope.setData(data);
          return scope;
        }, function(data) {
          throw new PException(data);
        });
      }
    },
    /**
     * مدیریت گروه‌های کاربر را ایجاد می‌کند
     */
    $group: {
      data: {},
      setData: function(data) {
        angular.extend(this.data, data);
      },
    },
  };
  return userService;
});
