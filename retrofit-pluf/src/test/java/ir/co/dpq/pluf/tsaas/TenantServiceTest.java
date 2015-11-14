package ir.co.dpq.pluf.tsaas;

import static ir.co.dpq.pluf.TestConstant.ADMIN_LOGIN;
import static ir.co.dpq.pluf.TestConstant.ADMIN_PASSWORD;
import static ir.co.dpq.pluf.TestConstant.API_URL;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.assertTrue;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Before;
import org.junit.Test;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.reflect.TypeToken;

import ir.co.dpq.pluf.DeserializerJson;
import ir.co.dpq.pluf.PErrorHandler;
import ir.co.dpq.pluf.PPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.saas.IPTenantService;
import ir.co.dpq.pluf.saas.PLibrary;
import ir.co.dpq.pluf.saas.PTenant;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import ir.co.dpq.pluf.wiki.PWikiPageItem;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class TenantServiceTest {

	// private IPLiberaryService libraryService;
	// private IPLabelService labelService;
	// private IPWikiBookService wikiBookService;
	// private IPWikiPageService wikiService;
	private IPUserService usr;
	// private IPCategoryService categoryService;
	private IPTenantService tenantService;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PLibrary>>() {
				}.getType(), new DeserializerJson<PLibrary>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PCategory>>() {
				}.getType(), new DeserializerJson<PCategory>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PLabel>>() {
				}.getType(), new DeserializerJson<PLabel>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPage>>() {
				}.getType(), new DeserializerJson<PWikiPage>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPageItem>>() {
				}.getType(), new DeserializerJson<PWikiPageItem>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiBook>>() {
				}.getType(), new DeserializerJson<PWikiBook>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PTenant>>() {
				}.getType(), new DeserializerJson<PTenant>());
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
		// this.wikiBookService = restAdapter.create(IPWikiBookService.class);
		// this.wikiService = restAdapter.create(IPWikiPageService.class);
		this.usr = restAdapter.create(IPUserService.class);
		// this.labelService = restAdapter.create(IPLabelService.class);
		// this.categoryService = restAdapter.create(IPCategoryService.class);
		// this.libraryService = restAdapter.create(IPLiberaryService.class);
		this.tenantService = restAdapter.create(IPTenantService.class);
	}

	@Test
	public void createTenantTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PTenant tenant = new PTenant();
		tenant.setTitle("title");
		tenant.setDescription("description");

		PTenant ctenant = tenantService.createTenant(tenant.toMap());
		assertNotNull(ctenant);
		assertEquals(tenant.getTitle(), ctenant.getTitle());
		assertEquals(tenant.getDescription(), ctenant.getDescription());
	}

	@Test
	public void getTenantTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PTenant tenant = new PTenant();
		tenant.setTitle("title");
		tenant.setDescription("description");

		PTenant ctenant = tenantService.createTenant(tenant.toMap());
		assertNotNull(ctenant);
		assertEquals(tenant.getTitle(), ctenant.getTitle());
		assertEquals(tenant.getDescription(), ctenant.getDescription());

		PTenant ctenant2 = tenantService.getTenant(ctenant.getId());
		assertNotNull(ctenant2);
		assertEquals(tenant.getTitle(), ctenant2.getTitle());
		assertEquals(tenant.getDescription(), ctenant2.getDescription());
	}

	@Test
	public void updateTenantTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PTenant tenant = new PTenant();
		tenant.setTitle("title");
		tenant.setDescription("description");

		PTenant ctenant = tenantService.createTenant(tenant.toMap());
		assertNotNull(ctenant);
		assertEquals(tenant.getTitle(), ctenant.getTitle());
		assertEquals(tenant.getDescription(), ctenant.getDescription());

		PTenant ctenant2 = tenantService.getTenant(ctenant.getId());
		assertNotNull(ctenant2);
		assertEquals(tenant.getTitle(), ctenant2.getTitle());
		assertEquals(tenant.getDescription(), ctenant2.getDescription());

		tenant.setTitle("title :" + Math.random());
		tenant.setDescription("Description :" + Math.random());
		ctenant2 = tenantService.updateTenant(ctenant.getId(), tenant.toMap());
		assertNotNull(ctenant2);
		assertEquals(tenant.getTitle(), ctenant2.getTitle());
		assertEquals(tenant.getDescription(), ctenant2.getDescription());
	}

	@Test
	public void findTenantTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PTenant tenant = new PTenant();
		tenant.setTitle("title");
		tenant.setDescription("description");

		PTenant ctenant = tenantService.createTenant(tenant.toMap());
		assertNotNull(ctenant);
		assertEquals(tenant.getTitle(), ctenant.getTitle());
		assertEquals(tenant.getDescription(), ctenant.getDescription());

		PTenant ctenant2 = tenantService.getTenant(ctenant.getId());
		assertNotNull(ctenant2);
		assertEquals(tenant.getTitle(), ctenant2.getTitle());
		assertEquals(tenant.getDescription(), ctenant2.getDescription());

		tenant.setTitle("title :" + Math.random());
		tenant.setDescription("Description :" + Math.random());
		ctenant2 = tenantService.updateTenant(ctenant.getId(), tenant.toMap());
		assertNotNull(ctenant2);
		assertEquals(tenant.getTitle(), ctenant2.getTitle());
		assertEquals(tenant.getDescription(), ctenant2.getDescription());

		PPaginatorParameter params = new PPaginatorParameter();
		PPaginatorPage<PTenant> tlist = tenantService.findTenant(params.toMap());
		assertNotNull(tlist);
		assertNotNull(tlist.getItems());
		assertTrue(tlist.getItems().size() > 0);
	}

}
