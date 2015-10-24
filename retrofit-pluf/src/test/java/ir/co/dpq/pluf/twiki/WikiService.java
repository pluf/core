package ir.co.dpq.pluf.twiki;

import static ir.co.dpq.pluf.TestConstant.ADMIN_LOGIN;
import static ir.co.dpq.pluf.TestConstant.ADMIN_PASSWORD;
import static ir.co.dpq.pluf.TestConstant.API_URL;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;
import java.util.HashMap;

import org.junit.Before;
import org.junit.Test;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.reflect.TypeToken;

import ir.co.dpq.pluf.DeserializerJson;
import ir.co.dpq.pluf.PErrorHandler;
import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.PPaginatorPage;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiPageService;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class WikiService {

	private IPWikiPageService wikiService;
	private IPUserService usr;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder
				/*
				 * این تبدیل برای صفحه بندی به کار گرفته می‌شود.
				 */
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPage>>() {
				}.getType(), new DeserializerJson<PWikiPage>())
				/*
				 * 
				 */
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
		this.wikiService = restAdapter.create(IPWikiPageService.class);
		this.usr = restAdapter.create(IPUserService.class);
	}

	@Test
	public void getMainPage() {
		PWikiPage page = wikiService.getWikiPage("fa", "main");
		assertNotNull(page);
	}

	@Test
	public void createPageTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());
	}

	@Test
	public void getPageTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.getWikiPage(cpage.getId());
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());
	}

	@Test
	public void deletePageTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.deleteWikiPage(cpage.getId());
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());
	}

	@Test(expected = PException.class)
	public void deletePageTest01() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.deleteWikiPage(cpage.getId());
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());

		wikiService.deleteWikiPage(cpage.getId());
	}

	@Test
	public void findPageTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		HashMap<String, Object> param = new HashMap<>();
		PPaginatorPage<PWikiPage> list = wikiService.findWikiPage(param);
		assertNotNull(list);
		assertNotNull(list.getItems());
	}
}
