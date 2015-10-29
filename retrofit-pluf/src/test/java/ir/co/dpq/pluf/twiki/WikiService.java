package ir.co.dpq.pluf.twiki;

import static ir.co.dpq.pluf.TestConstant.ADMIN_LOGIN;
import static ir.co.dpq.pluf.TestConstant.ADMIN_PASSWORD;
import static ir.co.dpq.pluf.TestConstant.API_URL;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.assertTrue;

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
import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.PPaginatorPage;
import ir.co.dpq.pluf.km.IPCategoryService;
import ir.co.dpq.pluf.km.IPLabelService;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiPageService;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class WikiService {

	private IPCategoryService categoryService;
	private IPLabelService labelService;
	private IPWikiPageService wikiService;
	private IPUserService usr;

	@Before
	public void createService() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PCategory>>() {
				}.getType(), new DeserializerJson<PCategory>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PLabel>>() {
				}.getType(), new DeserializerJson<PLabel>())//
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPage>>() {
				}.getType(), new DeserializerJson<PWikiPage>())//
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
		this.wikiService = restAdapter.create(IPWikiPageService.class);
		this.usr = restAdapter.create(IPUserService.class);
		this.labelService = restAdapter.create(IPLabelService.class);
		this.categoryService = restAdapter.create(IPCategoryService.class);
	}

	@Test
	public void getMainPage() {
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
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());
	}

	@Test
	public void getPageTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.getWikiPage(cpage.getId());
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());
	}

	@Test
	public void deletePageTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.deleteWikiPage(cpage.getId());
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());
	}

	@Test(expected = PException.class)
	public void deletePageTest01() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.deleteWikiPage(cpage.getId());
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());

		wikiService.deleteWikiPage(cpage.getId());
	}

	@Test
	public void findPageTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		HashMap<String, Object> param = new HashMap<>();
		PPaginatorPage<PWikiPage> list = wikiService.findWikiPage(param);
		assertNotNull(list);
		assertNotNull(list.getItems());
	}

	@Test
	public void addLabelTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PLabel label = new PLabel();
		label.setTitle("example");
		label.setDescription("label description");
		label.setColor("#FFFFFF");

		PLabel clabel = labelService.createLabel(label.toMap());
		assertNotNull(clabel);
		assertEquals(label.getTitle(), clabel.getTitle());
		assertEquals(label.getColor(), clabel.getColor());

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		wikiService.addLabelToPage(cpage.getId(), clabel.getId());
	}

	@Test
	public void delLabelTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PLabel label = new PLabel();
		label.setTitle("example");
		label.setDescription("label description");
		label.setColor("#FFFFFF");

		PLabel clabel = labelService.createLabel(label.toMap());
		assertNotNull(clabel);
		assertEquals(label.getTitle(), clabel.getTitle());
		assertEquals(label.getColor(), clabel.getColor());

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		wikiService.addLabelToPage(cpage.getId(), clabel.getId());

		Map<String, PLabel> labels = wikiService.getPageLabels(cpage.getId());
		assertNotNull(labels);
		assertTrue(labels.size() == 1);

		wikiService.deleteLabelFromPage(cpage.getId(), clabel.getId());
		labels = wikiService.getPageLabels(cpage.getId());
		assertNotNull(labels);
		assertTrue(labels.size() == 0);

	}

	@Test
	public void getLabelTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PLabel label = new PLabel();
		label.setTitle("example");
		label.setDescription("label description");
		label.setColor("#FFFFFF");

		PLabel clabel = labelService.createLabel(label.toMap());
		assertNotNull(clabel);
		assertEquals(label.getTitle(), clabel.getTitle());
		assertEquals(label.getColor(), clabel.getColor());

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		wikiService.addLabelToPage(cpage.getId(), clabel.getId());

		Map<String, PLabel> labels = wikiService.getPageLabels(cpage.getId());
		assertNotNull(labels);
		assertTrue(labels.size() == 1);

		PLabel[] items = labels.values().toArray(new PLabel[0]);
		assertEquals(clabel.getId(), items[0].getId());
	}

	@Test
	public void addCategoryTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PCategory category = new PCategory();
		category.setTitle("example");
		category.setDescription("label description");
		category.setColor("#FFFFFF");

		PCategory category2 = categoryService.createCategory(category.toMap());
		assertNotNull(category2);
		assertEquals(category.getTitle(), category2.getTitle());
		assertEquals(category.getColor(), category2.getColor());
		assertEquals(category.getDescription(), category2.getDescription());

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		wikiService.addCategoryToPage(cpage.getId(), category2.getId());
	}

	@Test
	public void getCategoriesTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PCategory category = new PCategory();
		category.setTitle("example");
		category.setDescription("label description");
		category.setColor("#FFFFFF");

		PCategory category2 = categoryService.createCategory(category.toMap());
		assertNotNull(category2);
		assertEquals(category.getTitle(), category2.getTitle());
		assertEquals(category.getColor(), category2.getColor());
		assertEquals(category.getDescription(), category2.getDescription());

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		wikiService.addCategoryToPage(cpage.getId(), category2.getId());

		Map<String, PCategory> cats = wikiService.getPageCategories(cpage.getId());
		assertNotNull(cats);
		assertTrue(cats.size() == 1);
	}

	@Test
	public void removeCategoryTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		// create label
		PCategory category = new PCategory();
		category.setTitle("example");
		category.setDescription("label description");
		category.setColor("#FFFFFF");

		PCategory category2 = categoryService.createCategory(category.toMap());
		assertNotNull(category2);
		assertEquals(category.getTitle(), category2.getTitle());
		assertEquals(category.getColor(), category2.getColor());
		assertEquals(category.getDescription(), category2.getDescription());

		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page.toMap());
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		wikiService.addCategoryToPage(cpage.getId(), category2.getId());

		Map<String, PCategory> cats = wikiService.getPageCategories(cpage.getId());
		assertNotNull(cats);
		assertTrue(cats.size() == 1);

		wikiService.deleteCategoryFromPage(cpage.getId(), category2.getId());
		cats = wikiService.getPageCategories(cpage.getId());
		assertNotNull(cats);
		assertTrue(cats.size() == 0);

	}
}
