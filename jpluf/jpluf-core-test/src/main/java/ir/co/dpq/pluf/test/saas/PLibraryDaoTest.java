package ir.co.dpq.pluf.test.saas;
//package ir.co.dpq.pluf.tsaas;
//
//import static ir.co.dpq.pluf.TestConstant.ADMIN_LOGIN;
//import static ir.co.dpq.pluf.TestConstant.ADMIN_PASSWORD;
//import static ir.co.dpq.pluf.TestConstant.API_URL;
//import static org.junit.Assert.assertEquals;
//import static org.junit.Assert.assertNotNull;
//import static org.junit.Assert.assertTrue;
//
//import java.net.CookieHandler;
//import java.net.CookieManager;
//import java.net.CookiePolicy;
//import java.util.HashMap;
//import java.util.Map;
//
//import org.junit.Before;
//import org.junit.Test;
//
//import com.google.gson.Gson;
//import com.google.gson.GsonBuilder;
//import com.google.gson.reflect.TypeToken;
//
//import ir.co.dpq.pluf.retrofit.DeserializerJson;
//import ir.co.dpq.pluf.retrofit.PErrorHandler;
//import ir.co.dpq.pluf.retrofit.PException;
//import ir.co.dpq.pluf.retrofit.PPaginatorPage;
//import ir.co.dpq.pluf.retrofit.km.PCategory;
//import ir.co.dpq.pluf.retrofit.km.PLabel;
//import ir.co.dpq.pluf.retrofit.saas.IPLiberaryService;
//import ir.co.dpq.pluf.retrofit.saas.PLibrary;
//import ir.co.dpq.pluf.retrofit.user.IPUserService;
//import ir.co.dpq.pluf.retrofit.user.PUser;
//import ir.co.dpq.pluf.retrofit.wiki.RWikiBook;
//import ir.co.dpq.pluf.retrofit.wiki.PWikiPage;
//import ir.co.dpq.pluf.retrofit.wiki.RWikiPageItem;
//import retrofit.RestAdapter;
//import retrofit.converter.GsonConverter;
//
///**
// * 
// * @author maso <mostafa.barmshory@dpq.co.ir>
// *
// */
//public class LibraryServiceTest {
//
//	private IPLiberaryService libraryService;
//	// private IPLabelService labelService;
//	// private IPWikiBookService wikiBookService;
//	// private IPWikiPageService wikiService;
//	private IPUserService usr;
//	// private IPCategoryService categoryService;
//
//	@Before
//	public void createService() {
//		CookieManager cookieManager = new CookieManager();
//		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
//		CookieHandler.setDefault(cookieManager);
//
//		GsonBuilder gsonBuilder = new GsonBuilder();
//		gsonBuilder//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<PLibrary>>() {
//				}.getType(), new DeserializerJson<PLibrary>())//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<PCategory>>() {
//				}.getType(), new DeserializerJson<PCategory>())//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<PLabel>>() {
//				}.getType(), new DeserializerJson<PLabel>())//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPage>>() {
//				}.getType(), new DeserializerJson<PWikiPage>())//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<RWikiPageItem>>() {
//				}.getType(), new DeserializerJson<RWikiPageItem>())//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<RWikiBook>>() {
//				}.getType(), new DeserializerJson<RWikiBook>());
//		Gson gson = gsonBuilder.create();
//
//		RestAdapter restAdapter = new RestAdapter.Builder()
//				// تعیین مبدل داده
//				.setConverter(new GsonConverter(gson))
//				// تعیین کنترل کننده خطا
//				.setErrorHandler(new PErrorHandler())
//				// تعیین آدرس سایت مورد نظر
//				.setEndpoint(API_URL)
//				// ایجاد یک نمونه
//				.build();
//		// ایجاد سرویس‌ها
//		// this.wikiBookService = restAdapter.create(IPWikiBookService.class);
//		// this.wikiService = restAdapter.create(IPWikiPageService.class);
//		this.usr = restAdapter.create(IPUserService.class);
//		// this.labelService = restAdapter.create(IPLabelService.class);
//		// this.categoryService = restAdapter.create(IPCategoryService.class);
//		this.libraryService = restAdapter.create(IPLiberaryService.class);
//	}
//
//	@Test
//	public void createLibraryTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PLibrary lib = new PLibrary();
//		lib.setName("angularjs");
//		lib.setVersion("1.14.0");
//		lib.setType(PLibrary.TYPE_JAVASCRITP);
//		lib.setMode(PLibrary.MODE_RELESE);
//		lib.setDescription("our main lib");
//		lib.setPath("/assets/lib/angularjs/angularjs.min.js");
//
//		PLibrary clib = libraryService.createLibrary(lib.toMap());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//	}
//
//	@Test
//	public void getLibraryTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PLibrary lib = new PLibrary();
//		lib.setName("angularjs");
//		lib.setVersion("1.14.0");
//		lib.setType(PLibrary.TYPE_JAVASCRITP);
//		lib.setMode(PLibrary.MODE_RELESE);
//		lib.setDescription("our main lib");
//		lib.setPath("/assets/lib/angularjs/angularjs.min.js");
//
//		PLibrary clib = libraryService.createLibrary(lib.toMap());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//
//		clib = libraryService.getLibrary(clib.getId());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//	}
//
//	@Test
//	public void updateLibraryTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PLibrary lib = new PLibrary();
//		lib.setName("angularjs");
//		lib.setVersion("1.14.0");
//		lib.setType(PLibrary.TYPE_JAVASCRITP);
//		lib.setMode(PLibrary.MODE_RELESE);
//		lib.setDescription("our main lib");
//		lib.setPath("/assets/lib/angularjs/angularjs.min.js");
//
//		PLibrary clib = libraryService.createLibrary(lib.toMap());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//
//		lib.setName("angularjs" + Math.random());
//		lib.setVersion("1.14.0" + Math.random());
//		lib.setType(PLibrary.TYPE_CSS);
//		lib.setMode(PLibrary.MODE_DEBUG);
//		lib.setDescription("our main lib" + Math.random());
//		lib.setPath("/assets/lib/angularjs/angularjs.min.js" + Math.random());
//
//		clib = libraryService.updateLibrary(clib.getId(), lib.toMap());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//	}
//
//	@Test
//	public void deleteLibraryTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PLibrary lib = new PLibrary();
//		lib.setName("angularjs");
//		lib.setVersion("1.14.0");
//		lib.setType(PLibrary.TYPE_JAVASCRITP);
//		lib.setMode(PLibrary.MODE_RELESE);
//		lib.setDescription("our main lib");
//		lib.setPath("/assets/lib/angularjs/angularjs.min.js");
//
//		PLibrary clib = libraryService.createLibrary(lib.toMap());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//
//		libraryService.deleteLibrary(clib.getId());
//	}
//
//	@Test(expected = PException.class)
//	public void deleteLibraryTest01() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PLibrary lib = new PLibrary();
//		lib.setName("angularjs");
//		lib.setVersion("1.14.0");
//		lib.setType(PLibrary.TYPE_JAVASCRITP);
//		lib.setMode(PLibrary.MODE_RELESE);
//		lib.setDescription("our main lib");
//		lib.setPath("/assets/lib/angularjs/angularjs.min.js");
//
//		PLibrary clib = libraryService.createLibrary(lib.toMap());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//
//		libraryService.deleteLibrary(clib.getId());
//		libraryService.deleteLibrary(clib.getId());
//	}
//
//	@Test
//	public void findLibraryTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PLibrary lib = new PLibrary();
//		lib.setName("angularjs");
//		lib.setVersion("1.14.0");
//		lib.setType(PLibrary.TYPE_JAVASCRITP);
//		lib.setMode(PLibrary.MODE_RELESE);
//		lib.setDescription("our main lib");
//		lib.setPath("/assets/lib/angularjs/angularjs.min.js");
//
//		PLibrary clib = libraryService.createLibrary(lib.toMap());
//		assertNotNull(clib);
//		assertEquals(lib.getName(), clib.getName());
//		assertEquals(lib.getVersion(), clib.getVersion());
//		assertEquals(lib.getType(), clib.getType());
//		assertEquals(lib.getMode(), clib.getMode());
//		assertEquals(lib.getDescription(), clib.getDescription());
//		assertEquals(lib.getPath(), clib.getPath());
//
//		Map<String, Object> params = new HashMap<>();
//		PPaginatorPage<PLibrary> books = libraryService.findLibrary(params);
//		assertNotNull(books);
//		assertNotNull(books.getItems());
//		assertTrue(books.getItems().size() > 0);
//	}
//}
