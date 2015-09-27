package ir.co.dpq.pluf;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import retrofit.RestAdapter;
import retrofit.RetrofitError;

public class PUserServiceTest {

	private IPUserService usr;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		String API_URL = "http://localhost:1396";
		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(API_URL).build();
		this.usr = restAdapter.create(IPUserService.class);
	}
	
	
	@Test
	public void getSessionUser(){
		PUser user = usr.getSessionUser();
		Assert.assertNotNull(user);
	}
	
	@Test
	public void login(){
		PUser user = usr.login("admin", "admin");
		Assert.assertNotNull(user);
		Assert.assertEquals("admin", user.getLogin());
	}
	
	@Test(expected=RetrofitError.class)
	public void loginFail(){
		PUser user = usr.login("Non user name", "bad password");
		Assert.assertNotNull(user);
		Assert.assertEquals("admin", user.getLogin());
	}
	
	@Test
	public void logout(){
		// Login 
		PUser user = usr.login("admin", "admin");
		Assert.assertNotNull(user);
		Assert.assertEquals("admin", user.getLogin());
		
		usr.logout();
	}
}



