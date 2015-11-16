package ir.co.dpq.pluf.retrofit;

import java.util.HashMap;
import java.util.Map;

import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.retrofit.user.RProfile;
import ir.co.dpq.pluf.retrofit.user.RUser;
import ir.co.dpq.pluf.retrofit.wiki.RWikiBook;
import ir.co.dpq.pluf.user.PProfile;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.PWikiBook;

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

	public static RProfile toRObject(PProfile profile) {
		if(profile instanceof RProfile)
			return (RProfile) profile;
		return new RProfile(profile);
	}

	public static RWikiBook toRObject(PWikiBook book) {
		if(book instanceof RWikiBook)
			return (RWikiBook) book;
		return new RWikiBook(book);
	}

	public static RPaginatorParameter toRObject(PPaginatorParameter param) {
		if(param instanceof RPaginatorParameter)
			return (RPaginatorParameter) param;
		return new RPaginatorParameter(param);
	}

	public static RUser toRObject(PUser user) {
		if(user instanceof RUser)
			return (RUser) user;
		return new RUser(user);
	}
}
