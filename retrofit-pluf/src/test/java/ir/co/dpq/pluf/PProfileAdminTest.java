package ir.co.dpq.pluf;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.user.IPProfileAdministrator;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PProfile;
import ir.co.dpq.pluf.user.PUser;
import retrofit.RestAdapter;

public class PProfileAdminTest {
	private IPProfileAdministrator profileAdmin;
	private IPUserService usr;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		String API_URL = "http://localhost:1396";
		RestAdapter restAdapter = new RestAdapter.Builder()
				// تعیین کنترل کننده خطا
				.setErrorHandler(new PErrorHandler())
				// تعیین آدرس سایت مورد نظر
				.setEndpoint(API_URL)
				// ایجاد یک نمونه
				.build();
		this.profileAdmin = restAdapter.create(IPProfileAdministrator.class);
		this.usr = restAdapter.create(IPUserService.class);
	}

	@Test
	public void getUserProfile(){
		PUser user = usr.login("admin", "admin");
		Assert.assertNotNull(user);
		
		PProfile profile = profileAdmin.getProfile(user.getId());
		Assert.assertNotNull(profile);
	}
}
