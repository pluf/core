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
//import java.io.File;
//import java.net.CookieHandler;
//import java.net.CookieManager;
//import java.net.CookiePolicy;
//import java.net.URISyntaxException;
//import java.net.URL;
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
//import ir.co.dpq.pluf.retrofit.PPaginatorPage;
//import ir.co.dpq.pluf.retrofit.RPaginatorParameter;
//import ir.co.dpq.pluf.retrofit.km.PCategory;
//import ir.co.dpq.pluf.retrofit.km.PLabel;
//import ir.co.dpq.pluf.retrofit.saas.IPTenantService;
//import ir.co.dpq.pluf.retrofit.saas.PLibrary;
//import ir.co.dpq.pluf.retrofit.saas.PResource;
//import ir.co.dpq.pluf.retrofit.saas.PTenant;
//import ir.co.dpq.pluf.retrofit.user.IPUserService;
//import ir.co.dpq.pluf.retrofit.user.PUser;
//import ir.co.dpq.pluf.retrofit.wiki.RWikiBook;
//import ir.co.dpq.pluf.retrofit.wiki.PWikiPage;
//import ir.co.dpq.pluf.retrofit.wiki.RWikiPageItem;
//import retrofit.RestAdapter;
//import retrofit.converter.GsonConverter;
//import retrofit.mime.TypedFile;
//
//public class TenantServiceTest {
//
//	// private IPLiberaryService libraryService;
//	// private IPLabelService labelService;
//	// private IPWikiBookService wikiBookService;
//	// private IPWikiPageService wikiService;
//	private IPUserService usr;
//	// private IPCategoryService categoryService;
//	private IPTenantService tenantService;
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
//				}.getType(), new DeserializerJson<RWikiBook>())//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<PTenant>>() {
//				}.getType(), new DeserializerJson<PTenant>());
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
//		// this.libraryService = restAdapter.create(IPLiberaryService.class);
//		this.tenantService = restAdapter.create(IPTenantService.class);
//	}
//
//	@Test
//	public void createTenantTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PTenant tenant = new PTenant();
//		tenant.setTitle("title");
//		tenant.setDescription("description");
//
//		PTenant ctenant = tenantService.createTenant(tenant.toMap());
//		assertNotNull(ctenant);
//		assertEquals(tenant.getTitle(), ctenant.getTitle());
//		assertEquals(tenant.getDescription(), ctenant.getDescription());
//	}
//
//	@Test
//	public void getTenantTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PTenant tenant = new PTenant();
//		tenant.setTitle("title");
//		tenant.setDescription("description");
//
//		PTenant ctenant = tenantService.createTenant(tenant.toMap());
//		assertNotNull(ctenant);
//		assertEquals(tenant.getTitle(), ctenant.getTitle());
//		assertEquals(tenant.getDescription(), ctenant.getDescription());
//
//		PTenant ctenant2 = tenantService.getTenant(ctenant.getId());
//		assertNotNull(ctenant2);
//		assertEquals(tenant.getTitle(), ctenant2.getTitle());
//		assertEquals(tenant.getDescription(), ctenant2.getDescription());
//	}
//
//	@Test
//	public void updateTenantTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PTenant tenant = new PTenant();
//		tenant.setTitle("title");
//		tenant.setDescription("description");
//
//		PTenant ctenant = tenantService.createTenant(tenant.toMap());
//		assertNotNull(ctenant);
//		assertEquals(tenant.getTitle(), ctenant.getTitle());
//		assertEquals(tenant.getDescription(), ctenant.getDescription());
//
//		PTenant ctenant2 = tenantService.getTenant(ctenant.getId());
//		assertNotNull(ctenant2);
//		assertEquals(tenant.getTitle(), ctenant2.getTitle());
//		assertEquals(tenant.getDescription(), ctenant2.getDescription());
//
//		tenant.setTitle("title :" + Math.random());
//		tenant.setDescription("Description :" + Math.random());
//		ctenant2 = tenantService.updateTenant(ctenant.getId(), tenant.toMap());
//		assertNotNull(ctenant2);
//		assertEquals(tenant.getTitle(), ctenant2.getTitle());
//		assertEquals(tenant.getDescription(), ctenant2.getDescription());
//	}
//
//	@Test
//	public void findTenantTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PTenant tenant = new PTenant();
//		tenant.setTitle("title");
//		tenant.setDescription("description");
//
//		PTenant ctenant = tenantService.createTenant(tenant.toMap());
//		assertNotNull(ctenant);
//		assertEquals(tenant.getTitle(), ctenant.getTitle());
//		assertEquals(tenant.getDescription(), ctenant.getDescription());
//
//		PTenant ctenant2 = tenantService.getTenant(ctenant.getId());
//		assertNotNull(ctenant2);
//		assertEquals(tenant.getTitle(), ctenant2.getTitle());
//		assertEquals(tenant.getDescription(), ctenant2.getDescription());
//
//		tenant.setTitle("title :" + Math.random());
//		tenant.setDescription("Description :" + Math.random());
//		ctenant2 = tenantService.updateTenant(ctenant.getId(), tenant.toMap());
//		assertNotNull(ctenant2);
//		assertEquals(tenant.getTitle(), ctenant2.getTitle());
//		assertEquals(tenant.getDescription(), ctenant2.getDescription());
//
//		RPaginatorParameter params = new RPaginatorParameter();
//		PPaginatorPage<PTenant> tlist = tenantService.findTenant(params.toMap());
//		assertNotNull(tlist);
//		assertNotNull(tlist.getItems());
//		assertTrue(tlist.getItems().size() > 0);
//	}
//
//	@Test
//	public void createResourceTest00() throws URISyntaxException {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		PTenant tenant = new PTenant();
//		tenant.setTitle("title");
//		tenant.setDescription("description");
//
//		PTenant ctenant = tenantService.createTenant(tenant.toMap());
//		assertNotNull(ctenant);
//		assertEquals(tenant.getTitle(), ctenant.getTitle());
//		assertEquals(tenant.getDescription(), ctenant.getDescription());
//
//		URL defaultImage = TenantServiceTest.class.getResource("/tenant/test.png");
//		File imageFile = new File(defaultImage.toURI());
//		TypedFile file = new TypedFile("application/binary", imageFile);
//		PResource resource = tenantService.createResource(ctenant.getId(), file, "description");
//		assertNotNull(resource);
//	}
//
//}
