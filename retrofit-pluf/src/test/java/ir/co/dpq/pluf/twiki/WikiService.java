package ir.co.dpq.pluf.twiki;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import static org.junit.Assert.*;
import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.PErrorHandler;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiPageService;
import ir.co.dpq.pluf.wiki.PWikiPage;
import retrofit.RestAdapter;

import static ir.co.dpq.pluf.TestConstant.*;

public class WikiService {

	private IPWikiPageService wikiService;
	private IPUserService usr;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		RestAdapter restAdapter = new RestAdapter.Builder()
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
		/*
		 * من فرض کردم که حتما صفحه اصلی با یک عنوان خاص وجود داره.
		 */
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
		page.setSummary("summery");
		page.setContent("Content");
		page.setContentType("text/plain");
		
		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());
	}
}
