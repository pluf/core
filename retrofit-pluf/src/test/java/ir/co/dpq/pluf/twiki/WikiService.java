package ir.co.dpq.pluf.twiki;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.PErrorHandler;
import ir.co.dpq.pluf.wiki.IPWikiService;
import ir.co.dpq.pluf.wiki.PWikiPage;
import retrofit.RestAdapter;

public class WikiService {

	private IPWikiService wikiService;

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
		this.wikiService = restAdapter.create(IPWikiService.class);
	}
	
	@Test
	public void getMainPage(){
		/*
		 * من فرض کردم که حتما صفحه اصلی با یک عنوان خاص وجود داره.
		 */
		PWikiPage page = wikiService.getWikiPage("fa", "main");
		Assert.assertNotNull(page);
	}
}
