package ir.co.dpq.pluf.retrofit.user;

import java.util.Map;

import ir.co.dpq.pluf.retrofit.CallbackWraper;
import retrofit.Callback;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;

/**
 * سیستم مدیریت پروفایل‌ها
 * 
 * 
 * @author maso
 *
 */
public interface IRProfileAdministrator {

	/**
	 * پروفایل کاربر تعیین شده را می‌دهد.
	 * 
	 * @param id
	 * @param callback
	 */
	@GET("/api/user/{id}/profile")
	void getProfile(@Path("id") long id, Callback<RProfile> callback);

	/**
	 * پروفایل کاربر تعیین شده را می‌دهد
	 * 
	 * @param l
	 * @return
	 */
	@GET("/api/user/{id}/profile")
	RProfile getProfile(@Path("id") long l);

	/**
	 * پروفایل کاربر تعیین شده را به روز می‌کند.
	 * 
	 * در صورتی که کاربر تعیین شده خود کاربر جاری باشد این فراخوانی معادل با به
	 * روز کردن اطلاعات پروفایل کاربری است.
	 * 
	 * برای دسترسی به پروفایل کاربر باید دسترسی مدیریت سیستم را داشت.
	 * 
	 * @param id
	 * @param params
	 * @param callback
	 */
	@FormUrlEncoded
	@POST("/api/user/{id}/profile")
	void updateProfile(@Path("id") long id, @FieldMap Map<String, Object> params, Callback<RProfile> callback);

	/**
	 * اطلاعات پروفایل کاربری را به روز می‌کند.
	 * 
	 * @param id
	 * @param params
	 * @param callback
	 */
	@FormUrlEncoded
	@POST("/api/user/{id}/profile")
	RProfile updateProfile(@Path("id") long id, @FieldMap Map<String, Object> params);
}
