//package ir.co.dpq.pluf.tjayab;
//
//
//import static org.junit.Assert.*;
//
//import java.net.CookieHandler;
//import java.net.CookieManager;
//import java.net.CookiePolicy;
//
//import org.junit.Before;
//import org.junit.Test;
//
//import com.google.gson.Gson;
//import com.google.gson.GsonBuilder;
//
//import ir.co.dpq.jayab.ILocationService;
//import ir.co.dpq.jayab.Location;
//import ir.co.dpq.jayab.Tag;
//import ir.co.dpq.pluf.DeserializerJson;
//import ir.co.dpq.pluf.PErrorHandler;
//import ir.co.dpq.pluf.PPaginatorPage;
//import ir.co.dpq.pluf.user.IPUserService;
//import ir.co.dpq.pluf.user.PUser;
//import retrofit.RestAdapter;
//import retrofit.converter.GsonConverter;
//
//public class LocationTagTest {
//
//	private ILocationService jayabService;
//	private IPUserService userService;
//
//	@SuppressWarnings("rawtypes")
//	@Before
//	public void createService() {
//		CookieManager cookieManager = new CookieManager();
//		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
//		CookieHandler.setDefault(cookieManager);
//
//		GsonBuilder gsonBuilder = new GsonBuilder();
//		gsonBuilder
//				/*
//				 * این تبدیل برای صفحه بندی به کار گرفته می‌شود.
//				 */
//				.registerTypeAdapter(PPaginatorPage.class, new DeserializerJson());
//		Gson gson = gsonBuilder.create();
//
//		RestAdapter restAdapter = new RestAdapter.Builder()
//				// تعیین مبدل داده
//				.setConverter(new GsonConverter(gson))
//				// تعیین کنترل کننده خطا
//				.setErrorHandler(new PErrorHandler())
//				// تعیین آدرس سایت مورد نظر
//				.setEndpoint(TestSettings.API_ADDRESS)
//				// ایجاد یک نمونه
//				.build();
//		this.userService = restAdapter.create(IPUserService.class);
//		this.jayabService = restAdapter.create(ILocationService.class);
//	}
//
//	/*
//	 * توی این تست یک مکان ایجاد می‌شه (به صورت تصادفی) بعد یک برچسب بهش زده می‌شه. 
//	 */
//	@Test
//	public void addTagTest00() {
//		PUser user = userService.login("admin", "admin");
//		assertNotNull(user);
//		assertTrue(user.isActive());
//		assertEquals("admin", user.getLogin());
//
//		// Create a location
//		Location l = new Location();
//		l.setName("Test name" + Math.random());
//		l.setDescription("This is test description" + Math.random());
//		l.setLatitude(Math.random());
//		l.setLongitude(Math.random());
//		
//		// Post to the server
//		Location nl = jayabService.createLocation(l.map());
//		assertNotNull(nl);
//		assertEquals(l.getName(), nl.getName());
//		assertEquals(l.getDescription(), nl.getDescription());
//		
//		// Add a tag
//		jayabService.addTag(nl.getId(), Tag.Key.AMENITY, Tag.Value.PARKING);
//	}
//	
//}
