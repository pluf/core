'use strict';

angular.module('pluf', [ 'pluf.paginator', 'pluf.user', 'pluf.core' ]);

/**
 * ساختار داده‌ای مورد نیاز برای تولید خطا و مدیریت آن را ایجاد می‌کند.
 */
angular.module("pluf.core", [])

/**
 * ساختارهای پایه برای تمام اشیا سیستم را ایجاد می‌کند. با استفاده از کلاس
 * بسیاری از فراخوانی‌های مشترک در یک کلاس جمع خواهد شد.
 */
.factory('PObject', function () {
	var pObject = function (data) {
		if (data) {
			this.setData(data);
		}
	};
	pObject.prototype = {
	  /*
		 * داده‌های دریافتی را تعیین می‌کند
		 */
	  setData : function (data) {
		  angular.extend(this, data);
	  },
	  /**
		 * تعیین می‌کند که آیا ساختارهای داده‌ای نشان دارند. زمانی که یک ساختار
		 * داده‌ای شناسه معتبر داشته باشد و سمت کارگذار ذخیره شده باشد به عنوان یک
		 * داده نشان دار در نظر گرفته می‌شود.
		 * 
		 * @returns {Boolean}
		 */
	  isAnonymous : function () {
		  return !(this.id && this.id > 0);
	  },
	  /**
		 * تعیین می‌کنه که آیا داده‌های کاربر منقضی شده یا نه. در صورتی که داده‌ها
		 * منقضی شده باشه دیگه نباید از آنها استفاده کرد.
		 */
	  expire : function () {
		  return false;
	  }
	};
	return pObject;
})

/**
 * ساختار پایه گزارش خطا در سیستم.
 */
.factory('PException', function (PObject) {
	var pException = function () {
		PObject.apply(this, arguments);
	};
	pException.prototype = new PObject();
	return pException;
})
/*
 * 
 */
.factory('PStatus', function (PObject) {
	var pStatus = function () {
		PObject.apply(this, arguments);
	};
	var pStatus = function (data) {
		if (data) {
			this.setData(data);
		}
	};
	pStatus.prototype = {
	  _s : 0,
	  preloading : function (m) {
		  this._m = m;
		  this._s = 0;
		  return this;
	  },
	  isLoading : function () {
		  return this._s == 0;
	  },
	  loaded : function (m) {
		  this._m = m;
		  this._s = 1;
		  return this;
	  },
	  isLoaded : function () {
		  return this._s == 1;
	  },
	  error : function (m) {
		  this._m = m;
		  this._s = 2
		  return this;
	  },
	  isError : function () {
		  return this._s == 2;
	  },
	  message : function () {
		  return this._m;
	  }
	}
	return pStatus;
})

/**
 * مدیریت داده‌های محلی کاربر را انجام می‌دهد. این داده‌ها به صورت محلی در
 * مرورگر ذخیره سازی می‌شوند.‌
 */
.service('$preference', function () {
	return this;
})

/**
 * Command Service (cs) سیستم مدیریت دستورها در سیستم را ایجاد می‌کند. دستور و
 * دستگیره از اکلیپس الهام شده است.
 */
.service('$act', function ($q, $timeout, PException) {
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
	this.getCommand = function (id) {
		var def = $q.defer();
		var scope = this;
		$timeout(function () {
			for (var i = 0; i < scope._commands.length; i++) {
				if (scope._commands[i].id == id) {
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
	this.category = function (key) {
		var def = $q.defer();
		var scope = this;
		$timeout(function () {
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
	this.command = function ($c) {
		this._commands.push($c);
		if (!('visible' in $c)) {
			$c.visible = function () {
				return true;
			};
		}
		if (!('enable' in $c)) {
			$c.enable = function () {
				return true;
			};
		}
		if (!('priority' in $c)) {
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
	this.commandHandler = function ($ch) {
		if (!($ch.commandId in this._handlers)) {
			this._handlers[$ch.commandId] = [];
		}
		this._handlers[$ch.commandId].push($ch);
		return this;
	}

	/**
	 * اجرای یک دستور
	 */
	this.execute = function ($ci) {
		var def = $q.defer();
		var scope = this;
		var args = Array.prototype.slice.call(arguments);
		args = args.slice(1);
		$timeout(function () {
			if (!($ci in scope._handlers)) {
				def.reject(new PException({
				  message : 'command not found :' + $ci,
				  statuse : 400,
				  code : 4404
				}));
				return;
			}
			// TODO: maso, 11394:‌ با استفاده از متد slice پیاده سازی شود.
			for (var i = 0; i in scope._handlers[$ci]; i++) {
				var handler = scope._handlers[$ci][i];
				handler['handle'].apply(handler, args);
			}
		}, 1);
		return def.promise;
	}
})
/**
 * مدیریت منوها را ایجاد می‌کند
 */
.service('$menu', function ($q, $timeout, $act) {
	this._menus = [];
	this.menu = function (id) {
		var def = $q.defer();
		var scope = this;
		$timeout(function () {
			if (!(id in scope._menus)) {
				scope._menus[id] = [];
			}
			def.resolve(scope._menus[id]);
		}, 1);
		return def.promise;
	}
	/**
	 * یک موجودیت جدید را به منو اضافه می‌کند.
	 */
	this.add = function (id, menu) {
		if (!(id in this._menus)) {
			this._menus[id] = [];
		}
		if ('command' in menu) {
			var scope = this;
			$act.getCommand(menu.command).then(function (command) {
				menu.active = function () {
					if (menu.params instanceof Array) {
						var args = [];
						args.push(menu.command);
						for (var i = 0; i < menu.params.length; i++) {
							args.push(menu.params[i]);
						}
						return $act.execute.apply($act, args);
					} else {
						return $act.execute(menu.command);
					}
				}
				if (!('visible' in menu)) {
					menu.visible = function () {
						return command.visible();
					}
				}
				if (!('enable' in menu)) {
					menu.enable = function () {
						return command.enable;
					}
				}
				if (!('label' in menu) && ('label' in command)) {
					menu.label = command.label;
				}
				if (!('priority' in menu)) {
					menu.priority = command.priority;
				}
				if (!('description' in menu)) {
					menu.priority = command.description;
				}
				// XXX: maso, 1394: خصوصیت‌های دیگر اضافه شود.
				scope._menus[id].push(menu);
			});
		} else if ('action' in menu) {
			menu.active = function () {
				return menu.action();
			}
			if (!('visible' in menu)) {
				menu.visible = function () {
					return true;
				}
			}
			// XXX: maso, 1394: خصوصیت‌های دیگر اضافه شود.
			this._menus[id].push(menu);
		}
		
		return this;
	}
})
/**
 * یک سیستم ساده است برای اعلام پیام در سیستم. با استفاده از این کلاس می‌توان
 * پیام‌های متفاوتی که در سیستم وجود دارد را به صورت همگانی اعلام کرد.
 */
.service('$notify', function ($rootScope, $timeout, $q) {
	/*
	 * فهرست شنودگرهای
	 */
	this._info = [];
	this._warning = [];
	this._debug = [];
	this._error = [];
	this._fire = function (list, args) {
		var deferred = $q.defer();
		$timeout(function () {
			for (var i = 0; i < list.length; i++) {
				list[i].apply(list[i], args);
			}
			deferred.resolve();
		}, 10);
		return deferred.promise;
	}
	/*
	 * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
	 */
	this.onInfo = function (listener) {
		this._info.push(listener);
		return this;
	}
	/**
	 * تمام واسطه‌های تعیین شده برای پیام را فراخوانی کرده و آنها را پیام ورودی
	 * آگاه می‌کند.
	 */
	this.info = function () {
		return this._fire(this._info, arguments);
	}
	/*
	 * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
	 */
	this.onWarning = function (listener) {
		this._warning.push(listener);
		return this;
	}
	/**
	 * تمام پیام‌های اخطاری که در سیستم تولید شده است را به سایر شنودگرها ارسال
	 * کرده و آنها را از بروز آن آگاه می‌کند.
	 */
	this.warning = function () {
		return this._fire(this._warning, arguments);
	}
	/*
	 * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
	 */
	this.onDebug = function (listener) {
		this._debug.push(listener);
		return this;
	}
	/**
	 * تمام پیام‌هایی که برای رفع خطا در سیستم تولید می‌شود را برای تمام شنودگرهای
	 * اضافه شده ارسال می‌کند.
	 */
	this.debug = function () {
		return this._fire(this._debug, arguments);
	}
	/*
	 * یک شنودگر جدید به فهرست شنودگرها اضافه می‌کند.
	 */
	this.onError = function (listener) {
		this._error.push(listener);
		return this;
	}
	/**
	 * تمام پیام‌های خطای تولید شده در سیتسم را برای تمام شوندگرهایی خطا صادر کرده
	 * و آنها را از آن مطلع می‌کند.
	 */
	this.error = function () {
		return this._fire(this._error, arguments);
	}
	/*
	 * یک رویداد خاص را در کل فضای نرم افزار انتشار می‌دهد. اولین پارامتر ورودی
	 * این تابع به عنوان نام و شناسه در نظر گرفت می‌شود و سایر پارامترها به عنوان
	 * پارامترهای ورودی آن.
	 */
	this.broadcast = function () {
		return $rootScope.$broadcast.apply($rootScope, arguments);
	}
});

/**
 * ساختار داده‌ای برای جستجو را تعیین می‌کند.
 */
angular.module("pluf.paginator", [])
/**
 * 
 */
.factory('PaginatorParameter', function () {
	var pagParam = function (paginatorParam) {
		if (paginatorParam) {
			this.setData(paginatorParam);
		} else {
			this.setData({});
		}
	};
	pagParam.prototype = {
	  param : {},
	  setData : function (paginatorParam) {
		  // angular.extend(this.param, paginatorParam);
		  this.param = paginatorParam;
	  },
	  setSize : function ($size) {
		  this.param['_px_c'] = $size;
		  return this;
	  },
	  setQuery : function ($query) {
		  this.param['_px_q'] = $query;
		  return this;
	  },
	  setPage : function ($page) {
		  this.param['_px_p'] = $page;
		  return this;
	  },
	  setOrder : function ($key, $order) {
		  this.param['_px_sk'] = $key;
		  this.param['_px_so'] = $order;
		  return this;
	  },
	  setFilter : function ($key, $value) {
		  this.param['_px_fk'] = $key;
		  this.param['_px_fv'] = $value;
		  return this;
	  },
	  getParameter : function () {
		  return this.param;
	  }
	};
	return pagParam;
})
/**
 * ساختار داده‌ای نرم‌افزار را ایجاد می‌کند.
 */
.factory('PaginatorPage', function (PObject) {
	var paginatorPage = function () {
		PObject.apply(this, arguments);
	};
	paginatorPage.prototype = new PObject();
	return paginatorPage;
});

/**
 * مدیریت کاربر: این سرویس تنها ابزارهایی را که برای مدیریت عادی یک کاربر مورد
 * نیاز است ارائه می‌کند. برای نمونه ورود به سیستم، خروج و یا به روز کردن
 * تنظیم‌های کاربری. مدیریت کاربران در سطح سیستم در سرویس‌های دیگر ارائه می‌شود.
 */
angular.module("pluf.user", [])

/**
 * 
 */
.factory('PProfile',
    function ($http, $httpParamSerializerJQLike, $q, PObject, PException) {
	    /**
			 * یک نمونه جدید از این موجودیت ایجاد می کند.
			 */
	    var pProfile = function () {
		    PObject.apply(this, arguments);
	    };
	    pProfile.prototype = new PObject();
	    
	    /**
			 * به روز رسانی پروفایل کاربری
			 */
	    pProfile.prototype.update = function (key, value) {
		    if (this.user.isAnonymous()) {
			    var deferred = $q.defer();
			    deferred.reject();
			    return deferred.promise;
		    }
		    var scope = this;
		    var param = {};
		    param[key] = value;
		    return $http({
		      method : 'POST',
		      url : '/api/user/profile',
		      data : $httpParamSerializerJQLike(param),
		      headers : {
			      'Content-Type' : 'application/x-www-form-urlencoded'
		      }
		    }).then(function (data) {
			    scope.setData(data);
			    return scope;
		    }, function (data) {
			    throw new PException(data);
		    });
	    }

	    return pProfile;
    })
/**
 * 
 */
.factory(
    'PUser',
    function ($http, $q, $httpParamSerializerJQLike, PObject, PProfile,
        PException) {
	    var pUser = function () {
		    PObject.apply(this, arguments);
	    };
	    pUser.prototype = new PObject();
	    
	    /**
			 * به روز کردن اطلاعات کاربر
			 */
	    pUser.prototype.update = function (key, value) {
		    var deferred = $q.defer();
		    var scope = this;
		    var param = {};
		    param[key] = value;
		    return $http({
		      method : 'POST',
		      url : '/api/user/' + this.id,
		      data : $httpParamSerializerJQLike(param),
		      headers : {
			      'Content-Type' : 'application/x-www-form-urlencoded'
		      }
		    }).then(function (data) {
			    scope.setData(data.data);
			    return scope;
		    }, function (data) {
			    throw new PException(data);
		    });
	    }

	    /**
			 * پروفایل کاربر را تعیین می‌کند.
			 * 
			 * @returns
			 */
	    pUser.prototype.profile = function () {
		    if (this.isAnonymous()) {
			    var deferred = $q.defer();
			    deferred.reject();
			    return deferred.promise;
		    }
		    if (this._prof && !this._prof.isAnonymous()) {
			    var deferred = $q.defer();
			    deferred.resolve(this._prof);
			    return deferred.promise;
		    }
		    var scope = this;
		    return $http({
		      method : 'GET',
		      url : '/api/user/' + this.id + '/profile',
		    }).then(function (res) {
			    scope._prof = new PProfile(res.data);
			    scope._prof.user = scope;
			    return scope._prof;
		    }, function (res) {
			    throw new PException(res.data);
		    });
	    }
	    return pUser;
    })

/**
 * 
 */
.service('$usr',
    function ($http, $httpParamSerializerJQLike, $q, $act, PUser, PException) {
	    /**
			 * تعیین می‌کنه که آیا کاربر جاری وارد سیستم شده یا نه.
			 */
	    this.isAnonymous = function () {
		    return (this._su == null) || this._su.isAnonymous();
	    }
	    /**
			 * ورود کاربر به سیستم
			 */
	    this.login = function ($login, $password) {
		    if (!this.isAnonymous()) {
			    var deferred = $q.defer();
			    deferred.resolve(this);
			    return deferred.promise;
		    }
		    var scope = this;
		    return $http({
		      method : 'POST',
		      url : '/api/user/login',
		      data : $httpParamSerializerJQLike({
		        'login' : $login,
		        'password' : $password
		      }),
		      headers : {
			      'Content-Type' : 'application/x-www-form-urlencoded'
		      }
		    }).then(function () {
			    return scope.session();
		    }, function (data) {
			    throw new PException(data);
		    }).then(function (data) {
			    // scope._su = new PUser(data.data);
			    return data;
		    }, function (data) {
			    throw new PException(data);
		    });
	    }
	    /**
			 * کاربری که در نشست تعیین شده است را بازیابی می‌کند.
			 * 
			 * @returns
			 */
	    this.session = function () {
		    var scope = this;
		    if (!this.isAnonymous()) {
			    var deferred = $q.defer();
			    deferred.resolve(this._su);
			    return deferred.promise;
		    }
		    return $http.get('/api/user/account').then(function (data) {
			    scope._su = new PUser(data.data);
			    return scope._su;
		    }, function (data) {
			    throw new PException(data);
		    });
	    }
	    /**
			 * خروج از سیستم
			 */
	    this.logout = function () {
		    if (this.isAnonymous()) {
			    var deferred = $q.defer();
			    deferred.resolve(this);
			    return deferred.promise;
		    }
		    var scope = this;
		    return $http.get('/api/user/logout').success(function (data) {
			    scope._su = null;
			    return scope._su;
		    }).error(function (data) {
			    throw new PException(data);
		    });
	    }

	    /**
			 * ثبت نام یک کاربر جدید
			 */
	    this.signup = function (detail) {
		    var scope = this;
		    return $http({
		      method : 'POST',
		      url : '/api/user/signup',
		      data : $httpParamSerializerJQLike(detail),
		      headers : {
			      'Content-Type' : 'application/x-www-form-urlencoded'
		      }
		    }).then(function (data) {
			    var user = new PUser(data.data);
			    return user;
		    }, function (data) {
			    throw new PException(data);
		    });
	    }
    })

/**
 * 
 */
.run(function ($usr, $act) {
	/*
	 * وارد شدن به عنوان یک کاربر به سیستم.
	 */
	$act.command({
	  id : 'pluf.user.login',
	  label : 'login',
	  description : 'login a user',
	  visible : function () {
		  return !$usr.isAnonymous();
	  },
	  category : 'usr',
	}).commandHandler({
	  commandId : 'pluf.user.login',
	  handle : function () {
		  if (arguments.length < 1) {
			  throw new PException('credentioal are not pass into the command.');
		  }
		  var a = arguments[0];
		  return $usr.login(a.username, a.password);
	  }
	});
	
	/**
	 * خروج کاربر جاری از سیستم
	 */
	$act.command({
	  id : 'pluf.user.logout',
	  label : 'logout',
	  description : 'logout the user',
	  visible : function () {
		  return !$usr.isAnonymous();
	  },
	  category : 'usr',
	}).commandHandler({
	  commandId : 'pluf.user.logout',
	  handle : function () {
		  return $usr.logout();
	  }
	});
	
	/**
	 * دستور به روز کردن اطلاعات کاربر جاری
	 */
	$act
	/*
	 * 
	 */
	.command({
	  id : 'pluf.user.update',
	  label : 'update',
	  description : 'update the current user',
	  visible : function () {
		  return !$usr.isAnonymous();
	  },
	})
	/*
	 * 
	 */
	.commandHandler({
	  commandId : 'pluf.user.update',
	  handle : function () {
		  if (arguments.length < 1) {
			  throw new PException('first param must be {key, value}');
		  }
		  var a = arguments[0];
		  return $usr.session().then(function (user) {
			  return user.update(a.key, a.value);
		  });
	  }
	});
	
	$act
	/*
	 * 
	 */
	.command({
	  id : 'pluf.user.profile.update',
	  label : 'update profile',
	  description : 'update the current user profile',
	  visible : function () {
		  return !$usr.isAnonymous();
	  },
	})
	/*
	 * 
	 */
	.commandHandler({
	  commandId : 'pluf.user.profile.update',
	  handle : function () {
		  if (arguments.length < 1) {
			  throw new PException('first param must be {key, value}');
		  }
		  var a = arguments[0];
		  return $usr.session().then(function (user) {
			  return user.profile();
		  }).then(function (profile) {
			  return profile.update(a.key, a.value);
		  });
	  }
	});
});
