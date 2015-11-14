//package ir.co.dpq.pluf.tinbox;
//
//import static ir.co.dpq.pluf.TestConstant.ADMIN_LOGIN;
//import static ir.co.dpq.pluf.TestConstant.ADMIN_PASSWORD;
//import static ir.co.dpq.pluf.TestConstant.API_URL;
//
//import java.net.CookieHandler;
//import java.net.CookieManager;
//import java.net.CookiePolicy;
//
//import org.junit.Assert;
//import org.junit.Before;
//import org.junit.Test;
//
//import ir.co.dpq.pluf.retrofit.PErrorHandler;
//import ir.co.dpq.pluf.retrofit.inbox.IPSystemMessageService;
//import ir.co.dpq.pluf.retrofit.inbox.PSystemMessage;
//import ir.co.dpq.pluf.retrofit.user.IPUserService;
//import ir.co.dpq.pluf.retrofit.user.PUser;
//import retrofit.RestAdapter;
//
//public class SystemMessageServiceTest {
//	private IPSystemMessageService systemMessageService;
//	private IPUserService usr;
//
//	@Before
//	public void createService() {
//		CookieManager cookieManager = new CookieManager();
//		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
//		CookieHandler.setDefault(cookieManager);
//
//		RestAdapter restAdapter = new RestAdapter.Builder()
//				// تعیین کنترل کننده خطا
//				.setErrorHandler(new PErrorHandler())
//				// تعیین آدرس سایت مورد نظر
//				.setEndpoint(API_URL)
//				// ایجاد یک نمونه
//				.build();
//		this.systemMessageService = restAdapter.create(IPSystemMessageService.class);
//		this.usr = restAdapter.create(IPUserService.class);
//	}
//
//	@Test
//	public void getUserProfile() {
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		Assert.assertNotNull(user);
//
//		PSystemMessage message = systemMessageService.newTestMessage();
//		Assert.assertNotNull(message);
//
//		PSystemMessage[] messages = systemMessageService.getAndDelete();
//		Assert.assertNotNull(messages);
//		Assert.assertTrue(messages.length >= 1);
//	}
//}
