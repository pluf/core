package ir.co.dpq.pluf.retrofit.user;

import java.util.Map;

import retrofit.Callback;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;

/**
 * ابزارهای مورد نیاز برای کار با پروفایل کاربر را فراهم می‌کند.
 * 
 * @note بر اساس نوع نرم افزار کاربردی ممکن است کاربر کی پروفایل مخصوص به خود
 *       داشته باشد از این رو نمی‌توان یک ساختار کلی برای پروفایل کاربری ارائه
 *       کرد. در اینجا از پروفایل پیش فرض سیستم به عنوان پروفایل کاربر استفاده
 *       شده است.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public interface IRProfileService {

	/**
	 * پروفایل کاربر جاری را تعیین می‌کند
	 * 
	 * برای استفاده از این فراخوانی باید وارد سیستم شده باشید در غیر این صورت با
	 * خطا روبرو خواهید شد.
	 * 
	 * @param callback
	 */
	@GET("/api/user/profile")
	void getProfile(Callback<RProfile> callback);

	/**
	 * پروفایل کاربر جاری را تعیین می‌کند.
	 * 
	 * @see #getProfile()
	 * @return
	 */
	@GET("/api/user/profile")
	RProfile getProfile();

	/**
	 * پروفایل کاربر جاری را به روز می‌کند.
	 * 
	 * @param id
	 * @param params
	 * @param callback
	 */
	@FormUrlEncoded
	@POST("/api/user/profile")
	void updateProfile(@FieldMap Map<String, Object> params, Callback<RProfile> callback);

	/**
	 * اطلاعات پروفایل کاربری را به روز می‌کند.
	 * 
	 * @param id
	 * @param params
	 * @param callback
	 */
	@FormUrlEncoded
	@POST("/api/user/profile")
	RProfile updateProfile(@FieldMap Map<String, Object> params);
}
