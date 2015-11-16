package ir.co.dpq.pluf.user;

import java.util.Map;

import javax.security.auth.callback.Callback;

import ir.co.dpq.pluf.IPCallback;

/**
 * تمام ابزارهای مدیریت کاربر را شامل می‌شود.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 */
public interface IPUserDao {

	/**
	 * ورود کاربر به سیستم
	 * 
	 * @note نگهداری حالت ورود کاربر با استفاده از کوکی انجام می‌شود. بنابر این
	 *       مطمئن شوید که مدیریت کوکی را فعال کرده‌اید.
	 * 
	 * @param username
	 * @param password
	 * @param callback
	 */
	void login(String username, String password, IPCallback<PUser> callback);

	/**
	 * ورود کاربر به سیستم
	 * 
	 * @see #login(String, String, Callback)
	 * @param username
	 * @param password
	 * @return
	 */
	PUser login(String username, String password);

	/**
	 * خروج کاربر از سیستم
	 * 
	 * 
	 * @param callback
	 */
	void logout(IPCallback<PUser> callback);

	/**
	 * خروج کاربر از سیستم
	 * 
	 * @see #logout(Callback)
	 * @return
	 */
	PUser logout();

	/**
	 * کاربر جاری را تعیین می‌کند
	 * 
	 * @param callback
	 */
	void getSessionUser(IPCallback<PUser> callback);

	/**
	 * کاربر جاری را تعیین می‌کند.
	 * 
	 * @see #getSessionUser(Callback)
	 * @return کاربر جاری
	 */
	PUser getSessionUser();

	/**
	 * کاربر جاری را تعیین می‌کند.
	 * 
	 * @see #getSessionUser(Callback)
	 * @return کاربر جاری
	 */
	void getUserInfo(Long userId, IPCallback<PUser> callback);

	PUser getUserInfo(Long userId);

	/**
	 * به روز کردن خصوصیت‌های کاربر.
	 * 
	 * با استفاده از این فراخوانی می‌توانید اطلاعات کاربری را به روز کنید.
	 * اطلاعات جدید کاربر به صورت یک نگاشت کلید مقدار تعیین می‌شود. کلیدهایی که
	 * برای متغیرها به کار گرفته می‌شود به صورت زیر است:
	 * 
	 * <ul>
	 * <li>first_name</li>
	 * <li>last_name</li>
	 * <li>email</li>
	 * <li>password</li>
	 * <li>language</li>
	 * <li>timezone</li>
	 * </ul>
	 * 
	 * برخی از فراخوانی‌ها برای اینجاد این پارامتر ایجاد شده است.
	 * 
	 * @see ir.co.dpq.pluf.Util#userUpdateParams(String, String, String, String,
	 *      String, String)
	 * @param params
	 * @param callback
	 */
	void update(PUser user, IPCallback<PUser> callback);

	/**
	 * اطلاعات کاربری را به روز می‌کند.
	 * 
	 * @see #update(Map, Callback)
	 * @param params
	 * @return
	 */
	PUser update(PUser user);

	/**
	 * یک کاربر جدید را در سیستم ثبت می‌کند.
	 * 
	 * اشتراکی‌های زیادی بین اطلاعات ثبت یک کاربر و به روز کردن یک کاربر وجود
	 * دارد. اما در حالت کلی اطلاعات ثبت یک کاربر جدید بیشتر از اطلاعاتی است که
	 * برای به روز کردن به کار گرفته می‌شود.
	 * 
	 * فهرستی از پارامترهای که برای ایجاد به کار گرفته می‌شود عبارتند از:
	 * 
	 * <ul>
	 * <li>login</li>
	 * <li>first_name</li>
	 * <li>last_name</li>
	 * <li>email</li>
	 * <li>password</li>
	 * <li>language</li>
	 * <li>timezone</li>
	 * </ul>
	 * 
	 * @param params
	 * @param callBack
	 */
	void signup(PUser user, IPCallback<PUser> callBack);

	/**
	 * یک کاربر جدید را در سیستم ثبت می‌کند.
	 * 
	 * @see #signup(Map, Callback)
	 * @param params
	 * @return
	 */
	PUser signup(PUser user);
}
