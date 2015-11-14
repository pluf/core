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
import ir.co.dpq.pluf.PPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.km.IPCategoryService;
import ir.co.dpq.pluf.km.IPLabelService;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.user.IPUserService;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiBookService;
import ir.co.dpq.pluf.wiki.IPWikiPageService;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import ir.co.dpq.pluf.wiki.PWikiPageItem;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class WikiBookService {

	private IPLabelService labelService;
	private IPWikiBookService wikiBookService;
	private IPWikiPageService wikiService;
	private IPUserService usr;
	private IPCategoryService categoryService;

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
				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPageItem>>() {
				}.getType(), new DeserializerJson<PWikiPageItem>())//
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
		// ایجاد سرویس‌ها
		this.wikiBookService = restAdapter.create(IPWikiBookService.class);
		this.wikiService = restAdapter.create(IPWikiPageService.class);
		this.usr = restAdapter.create(IPUserService.class);
		this.labelService = restAdapter.create(IPLabelService.class);
		this.categoryService = restAdapter.create(IPCategoryService.class);
	}

	@Test
	public void createBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
	}

	@Test
	public void getBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		PWikiBook book3 = wikiBookService.getWikiBook(book2.getId());
		assertNotNull(book3);
		assertEquals(book.getTitle(), book3.getTitle());
		assertEquals(book.getSummary(), book3.getSummary());
	}

	@Test
	public void deleteBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		wikiBookService.deleteWikiBook(book2.getId());
	}

	@Test
	public void updateBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		book.setTitle("new title" + Math.random());
		book.setSummary("summery" + Math.random());

		PWikiBook book3 = wikiBookService.updateWikiBook(book2.getId(), book.toMap());
		assertNotNull(book3);
		assertEquals(book.getTitle(), book3.getTitle());
		assertEquals(book.getSummary(), book3.getSummary());
	}

	@Test
	public void findBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		PPaginatorParameter param = new PPaginatorParameter();
		PPaginatorPage<PWikiPage> books = wikiBookService.findWikiBook(param.toMap());
		assertNotNull(books);
		assertNotNull(books.getItems());
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());

		wikiBookService.addLabelToBook(book2.getId(), clabel.getId());
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());

		wikiBookService.addLabelToBook(book2.getId(), clabel.getId());

		Map<String, PLabel> labels = wikiBookService.getBookLabels(book2.getId());
		assertNotNull(labels);
		assertTrue(labels.size() == 1);

		wikiBookService.deleteLabelFromBook(book2.getId(), clabel.getId());
		labels = wikiBookService.getBookLabels(book2.getId());
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());

		wikiBookService.addLabelToBook(book2.getId(), clabel.getId());

		Map<String, PLabel> labels = wikiBookService.getBookLabels(book2.getId());
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());

		wikiBookService.addCategoryToBook(book2.getId(), category2.getId());
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());

		wikiBookService.addCategoryToBook(book2.getId(), category2.getId());

		Map<String, PCategory> cats = wikiBookService.getBookCategories(book2.getId());
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());

		wikiBookService.addCategoryToBook(book2.getId(), category2.getId());

		Map<String, PCategory> cats = wikiBookService.getBookCategories(book2.getId());
		assertNotNull(cats);
		assertTrue(cats.size() == 1);

		wikiBookService.deleteCategoryFromBook(book2.getId(), category2.getId());
		cats = wikiBookService.getBookCategories(book2.getId());
		assertNotNull(cats);
		assertTrue(cats.size() == 0);
	}

	@Test
	public void addPageToBookTest00() {
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook cbook = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(cbook);
		assertEquals(book.getTitle(), cbook.getTitle());

		wikiBookService.addPageToBook(cbook.getId(), cpage.getId());

		Map<String, PWikiPageItem> pages = wikiBookService.getBookPages(cbook.getId());
		assertTrue(pages.size() == 1);
	}

	@Test
	public void deletePageToBookTest00() {
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

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook cbook = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(cbook);
		assertEquals(book.getTitle(), cbook.getTitle());

		wikiBookService.addPageToBook(cbook.getId(), cpage.getId());

		Map<String, PWikiPageItem> pages = wikiBookService.getBookPages(cbook.getId());
		assertTrue(pages.size() == 1);

		wikiBookService.deletePageFromBook(cbook.getId(), cpage.getId());
		pages = wikiBookService.getBookPages(cbook.getId());
		assertTrue(pages.size() == 0);

	}

	@Test
	public void addInterestedToBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook cbook = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(cbook);
		assertEquals(book.getTitle(), cbook.getTitle());

		wikiBookService.addInterestedUser(cbook.getId());
	}

	@Test
	public void removeInterestedFromBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook cbook = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(cbook);
		assertEquals(book.getTitle(), cbook.getTitle());

		wikiBookService.addInterestedUser(cbook.getId());

		wikiBookService.deleteInterestedUser(cbook.getId());
	}

	@Test
	public void getInterestedUsersOfBookTest00() {
		// Login
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);

		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook cbook = wikiBookService.createWikiBook(book.toMap());
		assertNotNull(cbook);
		assertEquals(book.getTitle(), cbook.getTitle());

		wikiBookService.addInterestedUser(cbook.getId());

		Map<String, PUser> interesteds = wikiBookService.getBookInteresteds(cbook.getId());
		assertNotNull(interesteds);
		assertTrue(interesteds.size() == 1);

		wikiBookService.deleteInterestedUser(cbook.getId());
		interesteds = wikiBookService.getBookInteresteds(cbook.getId());
		assertNotNull(interesteds);
		assertTrue(interesteds.size() == 0);
	}

}
