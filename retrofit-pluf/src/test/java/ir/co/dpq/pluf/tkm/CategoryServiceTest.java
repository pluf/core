package ir.co.dpq.pluf.tkm;

import static ir.co.dpq.pluf.TestConstant.ADMIN_LOGIN;
import static ir.co.dpq.pluf.TestConstant.ADMIN_PASSWORD;
import static ir.co.dpq.pluf.TestConstant.API_URL;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Before;
import org.junit.Test;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.reflect.TypeToken;

import ir.co.dpq.pluf.DeserializerJson;
import ir.co.dpq.pluf.PErrorHandler;
import ir.co.dpq.pluf.PPaginatorPage;
import ir.co.dpq.pluf.km.IPCategoryService;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class CategoryServiceTest {

	private IPCategoryService categoryService;
	private IPUserService usr;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PLabel>>() {
				}.getType(), new DeserializerJson<PLabel>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPage>>() {
				}.getType(), new DeserializerJson<PWikiPage>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiBook>>() {
				}.getType(), new DeserializerJson<PWikiBook>());
		Gson gson = gsonBuilder.create();

		RestAdapter restAdapter = new RestAdapter.Builder()
				// تعیین مبدل داده
				.setConverter(new GsonConverter(gson))
				// تعیین کنترل کننده خطا
				.setErrorHandler(new PErrorHandler())
				// تعیین آدرس سایت مورد نظر
				.setEndpoint(API_URL)
				// ایجاد یک نمونه
				.build();
		this.categoryService = restAdapter.create(IPCategoryService.class);
		this.usr = restAdapter.create(IPUserService.class);
	}

	@Test
	public void createCategoryTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PCategory category = new PCategory();
		category.setTitle("example");
		category.setDescription("label description");
		category.setColor("#FFFFFF");

		PCategory category2 = categoryService.createCategory(category.toMap());
		assertNotNull(category);
		assertEquals(category.getTitle(), category2.getTitle());
	}
	


	@Test
	public void createCategoryTest01() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PCategory category = new PCategory();
		category.setTitle("example");
		category.setDescription("label description");
		category.setColor("#FFFFFF");

		PCategory category2 = categoryService.createCategory(category.toMap());
		assertNotNull(category);
		assertEquals(category.getTitle(), category2.getTitle());
		assertEquals(category.getColor(), category2.getColor());
		assertEquals(category.getDescription(), category2.getDescription());
		
		for(int i = 0x0; i < 5; i++){
			PCategory sub = categoryService.createCategory(category2.getId(), category.toMap());
			assertNotNull(sub);
			assertEquals(category.getTitle(), sub.getTitle());
			assertEquals(category.getColor(), sub.getColor());
			assertEquals(category.getDescription(), sub.getDescription());
			assertEquals(category2.getId(), sub.getParent());
		}
	}

}
