package ir.co.dpq.pluf.retrofit;

import java.util.HashMap;
import java.util.Map;

import ir.co.dpq.pluf.PException;

/**
 * برخی از فراخوانی‌های پرکاربرد سیستم را ایجاد می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class Util {

	/**
	 * نگاشت مناسب برای به روز کردن اطلاعات کاربری را ایجاد می‌کند.
	 * 
	 * @param firstName
	 * @param lastName
	 * @param email
	 * @param password
	 * @param language
	 * @param timezone
	 * @return
	 */
	public static Map<String, Object> userUpdateParams(String firstName, String lastName, String email, String password,
			String language, String timezone) {
		HashMap<String, Object> params = new HashMap<String, Object>();
		// first name
		if (firstName != null) {
			params.put("first_name", firstName);
		}
		// last name
		if (lastName != null) {
			params.put("last_name", lastName);
		}
		// email
		if (email != null) {
			params.put("email", email);
		}
		// password
		if (password != null) {
			params.put("password", password);
		}
		// language
		if (language != null) {
			params.put("language", language);
		}
		return params;
	}

	/**
	 * پارامترهای مورد نیاز برای ثبت یک کاربر را ایجاد می‌کند.
	 * 
	 * @param login
	 * @param firstName
	 * @param lastName
	 * @param email
	 * @param password
	 * @param language
	 * @param timezone
	 * @return
	 */
	public static Map<String, Object> userSignupParams(String login, String firstName, String lastName, String email,
			String password, String language, String timezone) {
		Map<String, Object> params = userUpdateParams(firstName, lastName, email, password, language, timezone);
		// first name
		if (login != null) {
			params.put("login", firstName);
		} else {
			throw new PException("login is required in signup");
		}
		return params;
	}
}
