

# فهرست نرم‌افزارها

هر کسی می‌تواند این فراخوانی را انجام دهد

این فراخوانی به صورت زیر است:

	/app/list

این کار باید با متد GET انجام شود.

یک نمونه از خروجی این فراخوانی به صورت زیر است:

	{  
	   "0":{  
	      "id":1,
	      "level":0,
	      "access_count":0,
	      "validate":false,
	      "title":"Admin demo apartment",
	      "description":"Auto generated application",
	      "creation_dtime":"2015-06-19 18:44:07",
	      "modif_dtime":"2015-06-19 18:44:07"
	   }
	}

# فهرست اعضا

تنها افرادی که در سیستم ثبت شده‌اند قادرند از این فراخوانی استفاده کنند.

فراخوانی زیر برای این کار در نظر گرفته شده است:

	/saas/app/{application id}/memeber/list

یک نمونه خروجی این فراخوانی در زیر آورده شده است.

	{
	    "members": {},
	    "owners": {
	        "0": "admin"
	    },
	    "authorized": {}
	}
