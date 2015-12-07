'use strict';

angular.module('pluf.help', ['pluf'])

/**
 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
 */
.factory('PWikiPageItem', function(PObject) {
  var wikiPageItem = function(d) {
    if (d) {
      this.setData(d);
    }
  };
  wikiPageItem.prototype = new PObject();
  return wikiPageItem;
})
/*
 * 
 */
.factory('PWikiPage', function(PObject) {
  var wikiPage = function(d) {
    if (d) {
      this.setData(d);
    }
  };
  wikiPage.prototype = new PObject();
  wikiPage.prototype.render = function() {
    return markdown.toHTML(this.content);
  }
  return wikiPage;
})
/*
 * Wiki Book
 */
.factory(
        "PWikiBook",
        function(PObject, PException, PWikiPageItem, PaginatorPage, $http, $q,
                $timeout) {
          var pWikiBook = function() {
            PObject.apply(this, arguments);
          };
          pWikiBook.prototype = new PObject();
          /**
           * صحفه‌های کتاب را تعیین می‌کند.
           * 
           * @param param
           */
          pWikiBook.prototype.getFisrtPage = function() {
            var def = $q.defer();
            var scope = this;
            $timeout(function() {
              def.resolve(scope.items[0]);
            }, 1);
            return def.promise;
          }
          pWikiBook.prototype.retItem = function(id, data) {
            var item = null;
            for ( var i in this.items) {
              if (this.items[i].id == id) {
                item = this.items[i];
                break;
              }
            }
            if (!item) {
              item = new PWikiPageItem(data);
              this.items.push(item);
            }
            item.setData(data);
            return item;
          }
          pWikiBook.prototype.updateItem = function(id, data) {
            var item = null;
            for ( var i in this.items) {
              if (this.items[i].id == id) {
                item = this.items[i];
                break;
              }
            }
            if (!item) { return null; }
            item.setData(data);
            return item;
          }
          pWikiBook.prototype.pages = function() {
            var scope = this;
            return $http({
              method: 'GET',
              url: '/api/wiki/book/' + scope.id + '/pages',
            }).then(function(res) {
              scope.items = [];
              for (var i = 0; i < res.data.length; i++) {
                scope.retItem(res.data[i].id, res.data[i]);
              }
              return scope.items;
            }, function(data) {
              throw new PException(data);
            });
          }

          pWikiBook.prototype.addPage = function(page) {
            var scope = this;
            return $http({
              method: 'POST',
              url: '/api/wiki/book/' + scope.id + '/page/' + page.id,
            }).then(function(res) {
              return scope;
            }, function(data) {
              throw new PException(data);
            });
          }
          return pWikiBook;
        })
/**
 * مدیریت صفحه‌های ویکی را ایجاد می‌کند این مدیریت قادر است یک صفحه ویکی را در
 * اختیار کاربران قرار دهد.
 */
.service(
        '$help',
        function($http, $httpParamSerializerJQLike, $q, PException, PWikiPage,
                PWikiBook, PaginatorPage) {
          /*
           * کار با صفحه‌ها
           */
          this._ppage = {}
          this._getPage = function(id) {
            return this._ppage[id];
          }
          this._setPage = function(page) {
            this._ppage[page.id] = page;
          }
          this._retPage = function(id, data) {
            var instance = this._getPage(id);
            if (instance) {
              instance.setData(data);
            } else {
              instance = new PWikiPage(data);
              this._setPage(instance);
            }
            return instance;
          }

          /*
           * کار با کتابها
           */
          this._pbook = {}
          this._getBook = function(id) {
            return this._pbook[id];
          }
          this._setBook = function(page) {
            this._pbook[page.id] = page;
          }
          this._retBook = function(id, data) {
            var instance = this._getBook(id);
            if (instance) {
              instance.setData(data);
            } else {
              instance = new PWikiBook(data);
              this._setBook(instance);
            }
            return instance;
          }

          /* فراخوانی‌های عمومی */
          this.books = function(p) {
            var scope = this;
            return $http({
              method: 'GET',
              url: '/api/wiki/book/find',
              params: p.getParameter(),
            }).then(function(res) {
              var page = new PaginatorPage(res.data);
              var items = [];
              for (var i = 0; i < page.counts; i++) {
                var t = scope._retBook(page.items[i].id, page.items[i]);
                items.push(t);
              }
              page.items = items;
              return page;
            }, function(data) {
              throw new PException(data);
            });
          }

          this.createBook = function(b) {
            var scope = this;
            return $http({
              method: 'POST',
              url: '/api/wiki/book/create',
              data: $httpParamSerializerJQLike(b),
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            }).then(function(res) {
              var t = scope._retBook(res.data.id, res.data);
              return t;
            }, function(data) {
              throw new PException(data);
            });
          }
          this.updateBook = function(b, k, v) {
            var scope = this;
            var param = {};
            param[k] = v;
            return $http({
              method: 'POST',
              url: '/api/wiki/book/' + b.id,
              data: $httpParamSerializerJQLike(param),
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            }).then(function(res) {
              var t = scope._retBook(res.data.id, res.data);
              return t;
            }, function(data) {
              throw new PException(data);
            });
          }

          this.pages = function(p) {
            var scope = this;
            return $http({
              method: 'GET',
              url: '/api/wiki/page/find',
              params: p.getParameter(),
            }).then(function(res) {
              var page = new PaginatorPage(res.data);
              var items = [];
              for (var i = 0; i < page.counts; i++) {
                var t = scope._retPage(page.items[i].id, page.items[i]);
                items.push(t);
              }
              page.items = items;
              return page;
            }, function(data) {
              throw new PException(data);
            });
          }

          this.page = function(id) {
            var scope = this;
            return $http({
              method: 'GET',
              url: '/api/wiki/page/' + id,
            }).then(function(res) {
              var page = scope._retPage(res.data.id, res.data);
              return page;
            }, function(data) {
              throw new PException(data);
            });
          }

          this.createPage = function(p) {
            var scope = this;
            return $http({
              method: 'POST',
              url: '/api/wiki/page/create',
              data: $httpParamSerializerJQLike(p),
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            }).then(function(res) {
              var t = scope._retPage(res.data.id, res.data);
              return t;
            }, function(data) {
              throw new PException(data);
            });
          }
          this.updatePage = function(p, k, v) {
            var scope = this;
            var param = {};
            param[k] = v;
            return $http({
              method: 'POST',
              url: '/api/wiki/page/' + p.id,
              data: $httpParamSerializerJQLike(param),
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            }).then(function(res) {
              var b = scope._getBook(res.data.book);
              if (b) {
                b.updateItem(res.data.id, res.data);
              }
              return scope._retPage(res.data.id, res.data);
            }, function(data) {
              throw new PException(data);
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
});
