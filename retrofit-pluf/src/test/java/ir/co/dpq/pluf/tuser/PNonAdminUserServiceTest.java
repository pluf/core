package ir.co.dpq.pluf.tuser;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.PErrorHandler;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import retrofit.RestAdapter;

public class PNonAdminUserServiceTest {

	private IPUserService usr;
	private TestSettings testSettings = new TestSettings();

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		RestAdapter restAdapter = new RestAdapter.Builder()
				// تعیین کنترل کننده خطا
				.setErrorHandler(new PErrorHandler())
				// تعیین آدرس سایت مورد نظر
				.setEndpoint(testSettings.apiUrl)
				// ایجاد یک نمونه
				.build();
		this.usr = restAdapter.create(IPUserService.class);

		try {
			usr.signup(testSettings.user.getLogin(), testSettings.password, testSettings.user.getFirstName(),
					testSettings.user.getLastName(), testSettings.user.getEmail());
		} catch (Exception ex) {
			// TODO:
		}
		
	}

	@Test
	public void getSessionUser() {
		PUser user = usr.getSessionUser();
		Assert.assertNotNull(user);
	}

	@Test
	public void login() {
		PUser user = usr.login(testSettings.user.getLogin(), testSettings.password);
		Assert.assertNotNull(user);
		Assert.assertEquals(testSettings.user.getLogin(), user.getLogin());
		Assert.assertEquals(testSettings.user.getFirstName(), user.getFirstName());
		Assert.assertEquals(testSettings.user.getLastName(), user.getLastName());
		// Assert.assertEquals(testSettings.user.getEmail(), user.getEmail());
		Assert.assertEquals(testSettings.user.getLanguage(), user.getLanguage());
		Assert.assertEquals(testSettings.user.getTimezone(), user.getTimezone());
	}

	@Test
	public void logout() {
		// Login
		PUser user = usr.login(testSettings.user.getLogin(), testSettings.password);
		Assert.assertNotNull(user);
		Assert.assertEquals(testSettings.user.getLogin(), user.getLogin());

		usr.logout();
		PUser user2 = usr.getSessionUser();
		Assert.assertNull(user2.getLogin());
	}

	@Test
	public void updateFirstName() {
		PUser user = usr.login(testSettings.user.getLogin(), testSettings.password);

		// Test updating first_name
		String updatedField = "updated_first_name";
		usr.update(updatedField, null, null, null, null, null);
		Assert.assertNotEquals("Update first_name failed.", testSettings.user.getFirstName(), updatedField);

		usr.update(testSettings.user.getFirstName(), null, null, null, null, null);
		Assert.assertNotEquals("Reset first_name failed.", updatedField, testSettings.user.getFirstName());
	}

	@Test
	public void updateLastName() {
		PUser user = usr.login(testSettings.user.getLogin(), testSettings.password);

		// Test updating first_name
		String updatedField = "updated_last_name";
		usr.update(updatedField, null, null, null, null, null);
		Assert.assertNotEquals("Update last_name failed.", testSettings.user.getLastName(), updatedField);

		usr.update(testSettings.user.getFirstName(), null, null, null, null, null);
		Assert.assertNotEquals("Reset last_name failed.", updatedField, testSettings.user.getLastName());
	}

	@Test
	public void updateEmail() {
		PUser user = usr.login(testSettings.user.getLogin(), testSettings.password);

		// Test updating first_name
		String updatedField = "updated@mail.com";
		usr.update(updatedField, null, null, null, null, null);
		Assert.assertNotEquals("Update email failed.", testSettings.user.getEmail(), updatedField);

		usr.update(testSettings.user.getFirstName(), null, null, null, null, null);
		Assert.assertNotEquals("Reset email failed.", updatedField, testSettings.user.getEmail());
	}

	@Test
	public void getUserInfo() {
		PUser user = usr.login(testSettings.user.getLogin(), testSettings.password);

		PUser user2 = usr.getUserInfo(user.getId());
		Assert.assertNotNull(user);
		Assert.assertEquals(user.getLogin(), user2.getLogin());
		Assert.assertEquals(user.getFirstName(), user2.getFirstName());
		Assert.assertEquals(user.getLastName(), user2.getLastName());
		// Assert.assertEquals(user.getEmail(), user2.getEmail());
		Assert.assertEquals(user.getLanguage(), user2.getLanguage());
		Assert.assertEquals(user.getTimezone(), user2.getTimezone());
	}
}
