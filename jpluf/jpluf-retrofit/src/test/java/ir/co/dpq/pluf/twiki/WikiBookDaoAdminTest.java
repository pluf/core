package ir.co.dpq.pluf.twiki;

import static ir.co.dpq.pluf.TestConstant.API_URL;
import static ir.co.dpq.pluf.test.TestCoreConstant.ADMIN_LOGIN;
import static ir.co.dpq.pluf.test.TestCoreConstant.ADMIN_PASSWORD;
import static org.junit.Assert.assertNotNull;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Before;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import ir.co.dpq.pluf.PUserDaoRetrofit;
import ir.co.dpq.pluf.PWikiBookDaoRetrofit;
import ir.co.dpq.pluf.retrofit.PErrorHandler;
import ir.co.dpq.pluf.retrofit.user.IRUserService;
import ir.co.dpq.pluf.retrofit.wiki.IRWikiBookService;
import ir.co.dpq.pluf.test.wiki.PWikiBookDaoTest;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiBookDao;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class WikiBookDaoAdminTest extends PWikiBookDaoTest {

	IRUserService userSerivece;
	PUserDaoRetrofit userDaoRetrofit;

	IRWikiBookService wikiBookService;
	PWikiBookDaoRetrofit wikiBookDaoRetrofit;

	// private IPCategoryService categoryService;
	// private IPLabelService labelService;
	// private IRWikiPageService wikiService;

	public WikiBookDaoAdminTest() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.setDateFormat("yyyy-MM-dd HH:mm:ss");//
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
		// ایجاد سرویس‌ها
		this.userSerivece = restAdapter.create(IRUserService.class);
		this.wikiBookService = restAdapter.create(IRWikiBookService.class);

		// this.wikiService = restAdapter.create(IRWikiPageService.class);
		// this.labelService = restAdapter.create(IPLabelService.class);
		// this.categoryService = restAdapter.create(IPCategoryService.class);

		wikiBookDaoRetrofit = new PWikiBookDaoRetrofit();
		wikiBookDaoRetrofit.setWikiBookService(wikiBookService);
		
		userDaoRetrofit = new PUserDaoRetrofit();
		userDaoRetrofit.setUserService(userSerivece);
	}

	@Before
	public void loginWithAdmin() {
		// Login
		PUser user = userDaoRetrofit.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
	}

	@Override
	protected IPWikiBookDao getWikiBookInstance() {
		return wikiBookDaoRetrofit;
	}
}
