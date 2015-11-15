package ir.co.dpq.pluf.user;

import ir.co.dpq.pluf.IPCallback;

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
public interface IPProfileService {

	/**
	 * پروفایل کاربر جاری را تعیین می‌کند
	 * 
	 * برای استفاده از این فراخوانی باید وارد سیستم شده باشید در غیر این صورت با
	 * خطا روبرو خواهید شد.
	 * 
	 * @param callback
	 */
	void getProfile(IPCallback<PProfile> callback);

	/**
	 * پروفایل کاربر جاری را تعیین می‌کند.
	 * 
	 * @see #getProfile()
	 * @return
	 */
	PProfile getProfile();

	/**
	 * پروفایل کاربر جاری را به روز می‌کند.
	 * 
	 * @param id
	 * @param params
	 * @param callback
	 */
	void updateProfile(PProfile params, IPCallback<PProfile> callback);

	/**
	 * اطلاعات پروفایل کاربری را به روز می‌کند.
	 * 
	 * @param id
	 * @param params
	 * @param callback
	 */
	PProfile updateProfile(PProfile params);
}
