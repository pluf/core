package ir.co.dpq.pluf.user;

import ir.co.dpq.pluf.IPCallback;

/**
 * سیستم مدیریت پروفایل‌ها
 * 
 * 
 * @author maso
 *
 */
public interface IPProfileAdministrator {

	/**
	 * پروفایل کاربر تعیین شده را می‌دهد.
	 * 
	 * @param id
	 * @param callback
	 */
	void getProfile(Long id, IPCallback<PProfile> callback);

	/**
	 * پروفایل کاربر تعیین شده را می‌دهد
	 * 
	 * @param l
	 * @return
	 */
	PProfile getProfile(Long id);

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
	void updateProfile(PProfile profile, IPCallback<PProfile> callback);

	/**
	 * اطلاعات پروفایل کاربری را به روز می‌کند.
	 * 
	 * @param id
	 * @param params
	 * @param callback
	 */
	PProfile updateProfile(PProfile profile);
}
