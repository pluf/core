//package ir.co.dpq.pluf.test.jayab;
//
//
//import java.net.CookieHandler;
//import java.net.CookieManager;
//import java.net.CookiePolicy;
//
//import static org.junit.Assert.*;
//import org.junit.Before;
//import org.junit.Test;
//
//import ir.co.dpq.jayab.ILocationService;
//import ir.co.dpq.jayab.Location;
//import ir.co.dpq.pluf.PErrorHandler;
//import ir.co.dpq.pluf.user.IPUserService;
//import ir.co.dpq.pluf.user.PUser;
//import retrofit.RestAdapter;
//
//public class LocationTest {
//
//	private IPUserService userService;
//	private ILocationService jayabService;
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
//				.setEndpoint(TestSettings.API_ADDRESS)
//				// ایجاد یک نمونه
//				.build();
//		this.userService = restAdapter.create(IPUserService.class);
//		this.jayabService = restAdapter.create(ILocationService.class);
//
//	}
//
//	public void getLocation00() {
//		// Login
//		PUser user = userService.login("admin", "admin");
//		assertNotNull(user);
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
//		nl = jayabService.getLocation(nl.getId());
//		assertEquals(l.getName(), nl.getName());
//		assertEquals(l.getDescription(), nl.getDescription());
//	}
//
//	@Test
//	public void createLocation00() {
//		// Login
//		PUser user = userService.login("admin", "admin");
//		assertNotNull(user);
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
//	}
//
//	@Test
//	public void updateLocation00() {
//		// Login
//		PUser user = userService.login("admin", "admin");
//		assertNotNull(user);
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
//		// Update location
//		l.setName("name " + Math.random());
//		nl = jayabService.updateLocation(nl.getId(), l.map());
//		assertEquals(l.getName(), nl.getName());
//	}
//
//	@Test(expected = ir.co.dpq.pluf.PException.class)
//	public void deleteLocation00() {
//		// Login
//		PUser user = userService.login("admin", "admin");
//		assertNotNull(user);
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
//		// Update location
//		nl = jayabService.deleteLocation(nl.getId());
//		jayabService.getLocation(nl.getId());
//	}
//
//}
