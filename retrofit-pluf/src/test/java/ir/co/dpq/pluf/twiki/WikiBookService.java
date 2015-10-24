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
import java.util.Map;

import org.junit.Before;
import org.junit.Test;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.reflect.TypeToken;

import ir.co.dpq.pluf.DeserializerJson;
import ir.co.dpq.pluf.PErrorHandler;
import ir.co.dpq.pluf.PPaginatorPage;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiBookService;
import ir.co.dpq.pluf.wiki.IPWikiPageService;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class WikiBookService {

	private IPWikiBookService wikiBookService;
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
		// ایجاد سرویس‌ها
		this.wikiBookService = restAdapter.create(IPWikiBookService.class);
		this.wikiService = restAdapter.create(IPWikiPageService.class);
		this.usr = restAdapter.create(IPUserService.class);
	}

	@Test
	public void createBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
	}

	@Test
	public void getBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		PWikiBook book3 = wikiBookService.getWikiBook(book2.getId());
		assertNotNull(book3);
		assertEquals(book.getTitle(), book3.getTitle());
		assertEquals(book.getSummary(), book3.getSummary());
	}

	@Test
	public void deleteBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		wikiBookService.deleteWikiBook(book2.getId());
	}

	@Test
	public void updateBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		book.setTitle("new title" + Math.random());
		book.setSummary("summery" + Math.random());

		PWikiBook book3 = wikiBookService.updateWikiBook(book2.getId(), book.toMap());
		assertNotNull(book3);
		assertEquals(book.getTitle(), book3.getTitle());
		assertEquals(book.getSummary(), book3.getSummary());
	}

	@Test
	public void findBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		Map<String, Object> params = new HashMap<>();
		PPaginatorPage<PWikiPage> books = wikiBookService.findWikiBook(params);
		assertNotNull(books);
		assertNotNull(books.getItems());
	}
}
