package ir.co.dpq.pluf.tuser;

import static ir.co.dpq.pluf.TestConstant.ADMIN_LOGIN;
import static ir.co.dpq.pluf.TestConstant.ADMIN_PASSWORD;
import static ir.co.dpq.pluf.TestConstant.API_URL;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;
import java.util.HashMap;
import java.util.Map;

import org.junit.Before;
import org.junit.Test;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.retrofit.PErrorHandler;
import ir.co.dpq.pluf.retrofit.user.IPUserService;
import ir.co.dpq.pluf.retrofit.user.PUser;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class PUserServiceTest {

	private IPUserService usr;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.setDateFormat("yyyy-MM-dd HH:mm:ss")
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<PCategory>>() {
				// }.getType(), new DeserializerJson<PCategory>())//
				// .registerTypeAdapter(new TypeToken<PPaginatorPage<PLabel>>()
				// {
				// }.getType(), new DeserializerJson<PLabel>())//
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<PWikiPage>>() {
				// }.getType(), new DeserializerJson<PWikiPage>())//
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<RWikiPageItem>>() {
				// }.getType(), new DeserializerJson<RWikiPageItem>())//
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<RWikiBook>>() {
				// }.getType(), new DeserializerJson<RWikiBook>());
		;//
		Gson gson = gsonBuilder.create();

		RestAdapter restAdapter = new RestAdapter.Builder()//
				.setConverter(new GsonConverter(gson))//
				// تعیین کنترل کننده خطا
				.setErrorHandler(new PErrorHandler())
				// تعیین آدرس سایت مورد نظر
				.setEndpoint(API_URL)
				// ایجاد یک نمونه
				.build();
		this.usr = restAdapter.create(IPUserService.class);
	}

	@Test
	public void getSessionUser() {
		PUser user = usr.getSessionUser();
		assertNotNull(user);
	}

	@Test
	public void login() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals(ADMIN_LOGIN, user.getLogin());
	}

	@Test
	public void login01() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals(ADMIN_LOGIN, user.getLogin());
		assertNotNull(user.getLastLogin());
	}

	@Test(expected = PException.class)
	public void loginFail() {
		PUser user = usr.login("Non user name", "bad password");
		assertNotNull(user);
		assertEquals("admin", user.getLogin());
	}

	@Test
	public void logout() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals("admin", user.getLogin());

		usr.logout();
	}

	@Test
	public void updateUserFirstName() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals("admin", user.getLogin());

		String name = "maostafa" + Math.random();

		Map<String, Object> params = new HashMap<String, Object>();
		params.put("first_name", name);
		PUser nuser = usr.update(params);
		assertNotNull(nuser);
		assertEquals(name, nuser.getFirstName());
	}

	@Test
	public void updateUserEmail() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals(ADMIN_LOGIN, user.getLogin());

		String email = "mostafa.barmshory@dpq.co.ir";

		Map<String, Object> params = new HashMap<String, Object>();
		params.put("email", email);
		PUser nuser = usr.update(params);
		assertNotNull(nuser);
	}
}
