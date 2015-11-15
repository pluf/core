//package ir.co.dpq.pluf.tjayab;
//
//
//import static org.junit.Assert.assertEquals;
//import static org.junit.Assert.assertNotNull;
//import static org.junit.Assert.assertTrue;
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
//import ir.co.dpq.jayab.Vote;
//import ir.co.dpq.jayab.VoteSummary;
//import ir.co.dpq.pluf.DeserializerJson;
//import ir.co.dpq.pluf.PErrorHandler;
//import ir.co.dpq.pluf.PException;
//import ir.co.dpq.pluf.PPaginatorPage;
//import ir.co.dpq.pluf.user.IPUserService;
//import ir.co.dpq.pluf.user.PUser;
//import retrofit.RestAdapter;
//import retrofit.converter.GsonConverter;
//
//public class LocationVoteTest {
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
//		/*
//		 * این تبدیل برای صفحه بندی به کار گرفته می‌شود.
//		 */
//		.registerTypeAdapter(PPaginatorPage.class, new DeserializerJson());
//		Gson gson = gsonBuilder.create();
//
//		RestAdapter restAdapter = new RestAdapter.Builder()
//		// تعیین مبدل داده
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
//	 * توی این تست یک مکان ایجاد می‌شه (به صورت تصادفی) بعد یک برچسب بهش زده
//	 * می‌شه.
//	 */
//	@Test
//	public void addVoteTest00() {
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
//		Vote v = jayabService.setVote(nl.getId(), true);
//		assertNotNull(v);
//		assertEquals(true, v.isLike());
//	}
//
//	@Test
//	public void addVoteTest01() {
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
//		Vote v = jayabService.setVote(nl.getId(), true);
//		assertNotNull(v);
//		assertEquals(true, v.isLike());
//
//		Vote v2 = jayabService.getVoteState(nl.getId());
//		assertNotNull(v2);
//		assertEquals(v.isLike(), v2.isLike());
//	}
//
//	@Test
//	public void addVoteTest02() {
//		PUser user = userService.login("admin", "admin");
//		assertNotNull(user);
//		assertTrue(user.isActive());
//		assertEquals("admin", user.getLogin());
//
//		// Create a location
//		Location l = new Location();
//		l.setName("false" + Math.random());
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
//		Vote v = jayabService.setVote(nl.getId(), false);
//		assertNotNull(v);
//		assertEquals(false, v.isLike());
//
//		Vote v2 = jayabService.getVoteState(nl.getId());
//		assertNotNull(v2);
//		assertEquals(v.isLike(), v2.isLike());
//
//		v = jayabService.setVote(nl.getId(), true);
//		assertNotNull(v);
//		assertEquals(true, v.isLike());
//
//		v2 = jayabService.getVoteState(nl.getId());
//		assertNotNull(v2);
//		assertEquals(v.isLike(), v2.isLike());
//	}
//
//	@Test(expected = PException.class)
//	public void getVote00() {
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
//		jayabService.getVoteState(nl.getId());
//	}
//
//	@Test
//	public void getVotes00() {
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
//		// Set like vote
//		Vote v = jayabService.setVote(nl.getId(), true);
//		assertNotNull(v);
//		assertEquals(true, v.isLike());
//
//		// Check vote count
//		VoteSummary voteSummary = jayabService.getVoteSummary(nl.getId());
//		assertNotNull(voteSummary);
//		assertEquals(voteSummary.getLikes(), 1l);
//		assertEquals(voteSummary.getDislikes(), 0l);
//
//		// Set dislike vote
//		v = jayabService.setVote(nl.getId(), false);
//		assertNotNull(v);
//		assertEquals(false, v.isLike());
//
//		// Check vote count
//		voteSummary = jayabService.getVoteSummary(nl.getId());
//		assertNotNull(voteSummary);
//		assertEquals(voteSummary.getLikes(), 0l);
//		assertEquals(voteSummary.getDislikes(), 1l);
//
//		// remove vote
//		v = jayabService.deleteVote(nl.getId());
//		// assertNull(v);
//
//		// Check vote count
//		voteSummary = jayabService.getVoteSummary(nl.getId());
//		assertNotNull(voteSummary);
//		assertEquals(voteSummary.getLikes(), 0l);
//		assertEquals(voteSummary.getDislikes(), 0l);
//	}
//}
