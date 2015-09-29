package ir.co.dpq.pluf.user;

import java.util.Map;

import retrofit.Callback;
import retrofit.http.Field;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;

/**
 * تمام ابزارهای مدیریت کاربر را شامل می‌شود.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 */
public interface IPUserService {

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
	@FormUrlEncoded
	@POST("/api/user/login")
	void login(@Field("login") String username,
			@Field("password") String password, Callback<PUser> callback);

	/**
	 * ورود کاربر به سیستم
	 * 
	 * @see #login(String, String, Callback)
	 * @param username
	 * @param password
	 * @return
	 */
	@FormUrlEncoded
	@POST("/api/user/login")
	PUser login(@Field("login") String username,
			@Field("password") String password);

	/**
	 * خروج کاربر از سیستم
	 * 
	 * 
	 * @param callback
	 */
	@GET("/api/user/logout")
	void logout(Callback<PUser> callback);

	/**
	 * خروج کاربر از سیستم
	 * 
	 * @see #logout(Callback)
	 * @return
	 */
	@GET("/api/user/logout")
	PUser logout();

	/**
	 * کاربر جاری را تعیین می‌کند
	 * 
	 * @param callback
	 */
	@GET("/api/user/account")
	void getSessionUser(Callback<PUser> callback);

	/**
	 * کاربر جاری را تعیین می‌کند.
	 * 
	 * @see #getSessionUser(Callback)
	 * @return کاربر جاری
	 */
	@GET("/api/user/{userId}")
	PUser getUserInfo(@Path("userId") long userId);

	/**
	 * کاربر جاری را تعیین می‌کند.
	 * 
	 * @see #getSessionUser(Callback)
	 * @return کاربر جاری
	 */
	@GET("/api/user/{userId}")
	void getUserInfo(@Path("userId") long userId, Callback<PUser> callback);
	
	/**
	 * کاربر جاری را تعیین می‌کند.
	 * 
	 * @see #getSessionUser(Callback)
	 * @return کاربر جاری
	 */
	@GET("/api/user/account")
	PUser getSessionUser();
	
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
	@FormUrlEncoded
	@POST("/api/user/account")
	void update(@FieldMap Map<String, Object> params, Callback<PUser> callback);

	/**
	 * اطلاعات کاربری را به روز می‌کند.
	 * 
	 * @see #update(Map, Callback)
	 * @param params
	 * @return
	 */
	@FormUrlEncoded
	@POST("/api/user/account")
	PUser update(@FieldMap Map<String, Object> params);

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
	@FormUrlEncoded
	@POST("/api/user/signup")
	void signup(@FieldMap Map<String, Object> params, Callback<PUser> callBack);

	/**
	 * یک کاربر جدید را در سیستم ثبت می‌کند.
	 * 
	 * @see #signup(Map, Callback)
	 * @param params
	 * @return
	 */
	@FormUrlEncoded
	@POST("/api/user/signup")
	PUser signup(@FieldMap Map<String, Object> params);

	/**
	 * @deprecated use {@link #update(Map, Callback)}
	 */
	@FormUrlEncoded
	@POST("/api/user/account")
	void update(@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email,
			@Field("password") String password,
			@Field("language") String language,
			@Field("timezone") String timezone, Callback<PUser> callback);

	/**
	 * 
	 * @deprecated use {@link #update(Map)}
	 * @param firstName
	 * @param lastName
	 * @param email
	 * @param password
	 * @param language
	 * @param timezone
	 * @return
	 */
	@FormUrlEncoded
	@POST("/api/user/account")
	PUser update(@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email,
			@Field("password") String password,
			@Field("language") String language,
			@Field("timezone") String timezone);

	/**
	 * 
	 * @deprecated {@link #signup(Map, Callback)}
	 * @param uername
	 * @param password
	 * @param firstName
	 * @param lastName
	 * @param email
	 * @param callBack
	 */
	@FormUrlEncoded
	@POST("/api/user/signup")
	void signup(@Field("login") String uername,
			@Field("password") String password,
			@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email,
			Callback<PUser> callBack);

	/**
	 * @deprecated use {@link #signup(Map)}
	 * @param uername
	 * @param password
	 * @param firstName
	 * @param lastName
	 * @param email
	 * @return
	 */
	@Deprecated
	@FormUrlEncoded
	@POST("/api/user/signup")
	PUser signup(@Field("login") String uername,
			@Field("password") String password,
			@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email);
}
