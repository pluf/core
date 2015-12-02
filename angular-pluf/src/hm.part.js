'use strict';

angular
        .module('epartment', ['pluf'])
        .factory(
                '$hmpart',
                function($rootScope, $http, $window, $q, $tenant, HMPart, PaginatorPage,
                        PaginatorParameter) {
                  var partService = function(){
                    
                  };
                  partService.prototype = {
                    _pool: {},
                    _default: 0,
                    _get: function(id) {
                      return this._pool[id];
                    },
                    _retrieveInstance: function(id, partData) {
                      var instance = this._pool[id];
                      if (instance) {
                        instance.setData(partData);
                      } else {
                        instance = new HMPart(partData);
                        this._pool[id] = instance;
                      }
                      return instance;
                    },
                    _search: function($paginatorParam) {
                      return $http({
                        method: 'GET',
                        url: '/api/hm/' + this._application.id + '/part/list',
                        params: $paginatorParam.getParameter(),
                        headers: {
                          'Content-Type': 'application/x-www-form-urlencoded'
                        }
                      }).then(function(res) {
                        var page = new PaginatorPage(res.data);
                        return page;
                      }, function(res) {
                        alert(res);
                      });
                    },
                    _loadDefault: function(deferred) {
                      var scope = this;
                      $http.get('/api/hm/part/active').success(
                              function(partData) {
                                var part = scope._retrieveInstance(partData.id,
                                        partData);
                                deferred.resolve(partData);
                                scope._default = part.id;
                                $rootScope.$broadcast('HMPart.Default.Changed',
                                        part);
                              }).error(function() {
                        deferred.reject();
                      });
                    },
                    _createPart: function(part_number, part_title, part_count,
                            deferred) {
                      var scope = this;
                      return $http({
                        method: 'POST',
                        url: '/api/hm/' + this._application.id + '/part',
                        data: $.param({
                          'part_number': part_number,
                          'title': part_title,
                          'count': part_count,
                        }),
                        headers: {
                          'Content-Type': 'application/x-www-form-urlencoded'
                        }
                      }).then(function(res) {
                        var data = res.data;
                        var part = scope._retrieveInstance(data.id, data);
                        deferred.resolve(part);
                      }, function(res) {
                        alert(res);
                        deferred.reject();
                      });
                    },
                    _updatePart: function(part, key, value, deferred) {
                      var scope = this;
                      var par = {};
                      par[key] = value;
                      return $http(
                              {
                                method: 'POST',
                                url: '/api/hm/' + this._application.id
                                        + '/part/' + part.id,
                                data: $.param(par),
                                headers: {
                                  'Content-Type': 'application/x-www-form-urlencoded'
                                }
                              }).then(function(res) {
                        var data = res.data;
                        var part = scope._retrieveInstance(data.id, data);
                        deferred.resolve(part);
                      }, function(res) {
                        alert(res);
                        deferred.reject();
                      });
                    },
                    /* فراخوانی‌های عمومی */
                    /**
                     * نرم‌افزار پیش فرض را تعیین می‌کند.
                     */
                    setApplication: function($application) {
                      if (typeof $application === 'undefined') return;
                      this._application = $application;
                    },

                    /**
                     * فهرستی از واحدها را تعیین می‌کند.
                     */
                    list: function($size, $page) {
                      var $pag = new PaginatorParameter();
                      return this._search($pag.setSize($size).setPage($page));
                    },
                    /**
                     * جستجو در واحدها را انجام می‌دهد
                     */
                    search: function($size, $page, $query) {
                      var $pag = new PaginatorParameter();
                      $pag.setSize($size).setPage($page).setQuery($query);
                      return this._search($pag);
                    },
                    gotoPart: function($partId) {
                      var scope = this;
                      return $http.get('/api/hm/part/active/' + $partId)
                              .success(
                                      function(partData) {
                                        $window.location.href = '/'
                                                + scope._application.id
                                                + "/part.html";
                                      });
                    },
                    defaultPart: function() {
                      var deferred = $q.defer();
                      if (this._default === 0) {
                        this._loadDefault(deferred);
                      } else {
                        deferred.resolve(this._get(this._default));
                      }
                      return deferred.promise;
                    },
                    create: function(part_number, part_title, part_count) {
                      var deferred = $q.defer();
                      this._createPart(part_number, part_title, part_count,
                              deferred);
                      return deferred.promise;
                    },
                    update: function(part, key, value) {
                      var deferred = $q.defer();
                      this._updatePart(part, key, value, deferred);
                      return deferred.promise;
                    }
                  }
                  return partService;
                });

/**
 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
 */
angular.module('epartment', []).factory('HMPart', function($rootScope) {
  var object = function(partData) {
    if (partData) {
      this.setData(partData);
    }
  };
  object.prototype = {
    setData: function(partData) {
      angular.extend(this, partData);
    },
    isAvailable: function() {
      if (this.id && this.id > 0) { return true; }
      return false;
    }
  };
  return object;
});
