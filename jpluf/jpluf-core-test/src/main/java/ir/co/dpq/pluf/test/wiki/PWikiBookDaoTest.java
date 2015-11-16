package ir.co.dpq.pluf.test.wiki;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.assertTrue;

import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.wiki.IPWikiBookDao;
import ir.co.dpq.pluf.wiki.PWikiBook;

public abstract class PWikiBookDaoTest {

	IPWikiBookDao wikiBookDao;

	@Before
	public void initTest() {
		wikiBookDao = getWikiBookInstance();
	}

	/**
	 * ایجاد یک نمونه از سرویس
	 * 
	 * سایر پیاده سازی‌ها با بازنویسی این فراخوانی و ایجاد سرویس می‌توانند از
	 * این تست‌ها استفاده کنند.
	 * 
	 * @return
	 */
	protected abstract IPWikiBookDao getWikiBookInstance();

	@Test
	public void createBookTest00() {
		PWikiBook book = new PWikiBook();
		book.setTitle("My test");
		book.setSummary("This is an example wiki book");

		PWikiBook cbook = wikiBookDao.createWikiBook(book);
		assertNotNull(cbook);
		assertTrue(cbook.getId() > 0);
		assertEquals(book.getTitle(), cbook.getTitle());
		assertEquals(book.getSummary(), cbook.getSummary());
	}

	@Test
	public void createBookTest01() {
		// Login
		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookDao.createWikiBook(book);
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
	}

	@Test
	public void getBookTest00() {
		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookDao.createWikiBook(book);
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		PWikiBook book3 = wikiBookDao.getWikiBook(book2.getId());
		assertNotNull(book3);
		assertEquals(book.getTitle(), book3.getTitle());
		assertEquals(book.getSummary(), book3.getSummary());
	}

	@Test
	public void deleteBookTest00() {
		// Login
		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookDao.createWikiBook(book);
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		wikiBookDao.deleteWikiBook(book2);
	}

	@Test
	public void updateBookTest00() {
		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookDao.createWikiBook(book);
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		book2.setTitle("new title" + Math.random());
		book2.setSummary("summery" + Math.random());

		PWikiBook book3 = wikiBookDao.updateWikiBook(book2);
		assertNotNull(book3);
		assertEquals(book2.getTitle(), book3.getTitle());
		assertEquals(book2.getSummary(), book3.getSummary());
	}

	@Test
	public void findBookTest00() {
		PWikiBook book = new PWikiBook();
		book.setTitle("title");
		book.setSummary("summery");

		PWikiBook book2 = wikiBookDao.createWikiBook(book);
		assertNotNull(book2);
		assertEquals(book.getTitle(), book2.getTitle());
		assertEquals(book.getSummary(), book2.getSummary());

		PPaginatorParameter param = new PPaginatorParameter();
		IPPaginatorPage<PWikiBook> books = wikiBookDao.findWikiBook(param);
		assertNotNull(books);
		assertNotNull(books.getItems());
		assertTrue(books.getItems().size() > 0);
	}

	// @Test
	// public void addLabelTest00() {
	// // create label
	// PLabel label = new PLabel();
	// label.setTitle("example");
	// label.setDescription("label description");
	// label.setColor("#FFFFFF");
	//
	// PLabel clabel = labelService.createLabel(label);
	// assertNotNull(clabel);
	// assertEquals(label.getTitle(), clabel.getTitle());
	// assertEquals(label.getColor(), clabel.getColor());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook book2 = wikiBookDao.createWikiBook(book);
	// assertNotNull(book2);
	// assertEquals(book.getTitle(), book2.getTitle());
	//
	// wikiBookDao.addLabelToBook(book2, clabel);
	// }

	// @Test
	// public void delLabelTest00() {
	// // create label
	// PLabel label = new PLabel();
	// label.setTitle("example");
	// label.setDescription("label description");
	// label.setColor("#FFFFFF");
	//
	// PLabel clabel = labelService.createLabel(label);
	// assertNotNull(clabel);
	// assertEquals(label.getTitle(), clabel.getTitle());
	// assertEquals(label.getColor(), clabel.getColor());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook book2 = wikiBookDao.createWikiBook(book);
	// assertNotNull(book2);
	// assertEquals(book.getTitle(), book2.getTitle());
	//
	// wikiBookDao.addLabelToBook(book2, clabel);
	//
	// Map<String, PLabel> labels = wikiBookDao.getBookLabels(book2);
	// assertNotNull(labels);
	// assertTrue(labels.size() == 1);
	//
	// wikiBookDao.deleteLabelFromBook(book2, clabel);
	// labels = wikiBookDao.getBookLabels(book2);
	// assertNotNull(labels);
	// assertTrue(labels.size() == 0);
	// }

	// @Test
	// public void getLabelTest00() {
	// // create label
	// PLabel label = new PLabel();
	// label.setTitle("example");
	// label.setDescription("label description");
	// label.setColor("#FFFFFF");
	//
	// PLabel clabel = labelService.createLabel(label);
	// assertNotNull(clabel);
	// assertEquals(label.getTitle(), clabel.getTitle());
	// assertEquals(label.getColor(), clabel.getColor());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook book2 = wikiBookDao.createWikiBook(book);
	// assertNotNull(book2);
	// assertEquals(book.getTitle(), book2.getTitle());
	//
	// wikiBookDao.addLabelToBook(book2, clabel);
	//
	// Map<String, PLabel> labels = wikiBookDao.getBookLabels(book2);
	// assertNotNull(labels);
	// assertTrue(labels.size() == 1);
	//
	// PLabel[] items = labels.values().toArray(new PLabel[0]);
	// assertEquals(clabel, items[0]);
	// }

	// @Test
	// public void addCategoryTest00() {
	// // create label
	// PCategory category = new PCategory();
	// category.setTitle("example");
	// category.setDescription("label description");
	// category.setColor("#FFFFFF");
	//
	// PCategory category2 = categoryService.createCategory(category);
	// assertNotNull(category2);
	// assertEquals(category.getTitle(), category2.getTitle());
	// assertEquals(category.getColor(), category2.getColor());
	// assertEquals(category.getDescription(), category2.getDescription());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook book2 = wikiBookDao.createWikiBook(book);
	// assertNotNull(book2);
	// assertEquals(book.getTitle(), book2.getTitle());
	//
	// wikiBookDao.addCategoryToBook(book2, category2);
	// }

	// @Test
	// public void getCategoriesTest00() {
	// // create label
	// PCategory category = new PCategory();
	// category.setTitle("example");
	// category.setDescription("label description");
	// category.setColor("#FFFFFF");
	//
	// PCategory category2 = categoryService.createCategory(category);
	// assertNotNull(category2);
	// assertEquals(category.getTitle(), category2.getTitle());
	// assertEquals(category.getColor(), category2.getColor());
	// assertEquals(category.getDescription(), category2.getDescription());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook book2 = wikiBookDao.createWikiBook(book);
	// assertNotNull(book2);
	// assertEquals(book.getTitle(), book2.getTitle());
	//
	// wikiBookDao.addCategoryToBook(book2, category2);
	//
	// Map<String, PCategory> cats = wikiBookDao.getBookCategories(book2);
	// assertNotNull(cats);
	// assertTrue(cats.size() == 1);
	// }

	// @Test
	// public void removeCategoryTest00() {
	// // create label
	// PCategory category = new PCategory();
	// category.setTitle("example");
	// category.setDescription("label description");
	// category.setColor("#FFFFFF");
	//
	// PCategory category2 = categoryService.createCategory(category);
	// assertNotNull(category2);
	// assertEquals(category.getTitle(), category2.getTitle());
	// assertEquals(category.getColor(), category2.getColor());
	// assertEquals(category.getDescription(), category2.getDescription());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook book2 = wikiBookDao.createWikiBook(book);
	// assertNotNull(book2);
	// assertEquals(book.getTitle(), book2.getTitle());
	//
	// wikiBookDao.addCategoryToBook(book2, category2);
	//
	// Map<String, PCategory> cats = wikiBookDao.getBookCategories(book2);
	// assertNotNull(cats);
	// assertTrue(cats.size() == 1);
	//
	// wikiBookDao.deleteCategoryFromBook(book2, category2);
	// cats = wikiBookDao.getBookCategories(book2);
	// assertNotNull(cats);
	// assertTrue(cats.size() == 0);
	// }

	// @Test
	// public void addPageToBookTest00() {
	// // create page
	// PWikiPage page = new PWikiPage();
	// page.setTitle("example");
	// page.setSummary("summary");
	// page.setContent("Content");
	// page.setContentType("text/plain");
	//
	// PWikiPage cpage = wikiService.createWikiPage(page);
	// assertNotNull(cpage);
	// assertEquals(page.getSummary(), cpage.getSummary());
	// assertEquals(page.getContent(), cpage.getContent());
	// assertEquals(page.getContentType(), cpage.getContentType());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook cbook = wikiBookDao.createWikiBook(book);
	// assertNotNull(cbook);
	// assertEquals(book.getTitle(), cbook.getTitle());
	//
	// wikiBookDao.addPageToBook(cbook, cpage);
	//
	// Map<String, RWikiPageItem> pages = wikiBookDao.getBookPages(cbook);
	// assertTrue(pages.size() == 1);
	// }

	// @Test
	// public void deletePageToBookTest00() {
	// // create page
	// PWikiPage page = new PWikiPage();
	// page.setTitle("example");
	// page.setSummary("summary");
	// page.setContent("Content");
	// page.setContentType("text/plain");
	//
	// PWikiPage cpage = wikiService.createWikiPage(page);
	// assertNotNull(cpage);
	// assertEquals(page.getSummary(), cpage.getSummary());
	// assertEquals(page.getContent(), cpage.getContent());
	// assertEquals(page.getContentType(), cpage.getContentType());
	//
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook cbook = wikiBookDao.createWikiBook(book);
	// assertNotNull(cbook);
	// assertEquals(book.getTitle(), cbook.getTitle());
	//
	// wikiBookDao.addPageToBook(cbook, cpage);
	//
	// Map<String, RWikiPageItem> pages = wikiBookDao.getBookPages(cbook);
	// assertTrue(pages.size() == 1);
	//
	// wikiBookDao.deletePageFromBook(cbook, cpage);
	// pages = wikiBookDao.getBookPages(cbook);
	// assertTrue(pages.size() == 0);
	// }

	// @Test
	// public void addInterestedToBookTest00() {
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook cbook = wikiBookDao.createWikiBook(book);
	// assertNotNull(cbook);
	// assertEquals(book.getTitle(), cbook.getTitle());
	//
	// wikiBookDao.addInterestedUser(cbook);
	// }

	// @Test
	// public void removeInterestedFromBookTest00() {
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook cbook = wikiBookDao.createWikiBook(book);
	// assertNotNull(cbook);
	// assertEquals(book.getTitle(), cbook.getTitle());
	//
	// wikiBookDao.addInterestedUser(cbook);
	//
	// wikiBookDao.deleteInterestedUser(cbook);
	// }

	// @Test
	// public void getInterestedUsersOfBookTest00() {
	// PWikiBook book = new PWikiBook();
	// book.setTitle("title");
	// book.setSummary("summery");
	//
	// PWikiBook cbook = wikiBookDao.createWikiBook(book);
	// assertNotNull(cbook);
	// assertEquals(book.getTitle(), cbook.getTitle());
	//
	// wikiBookDao.addInterestedUser(cbook);
	//
	// Map<String, PUser> interesteds = wikiBookDao.getBookInteresteds(cbook);
	// assertNotNull(interesteds);
	// assertTrue(interesteds.size() == 1);
	//
	// wikiBookDao.deleteInterestedUser(cbook);
	// interesteds = wikiBookDao.getBookInteresteds(cbook);
	// assertNotNull(interesteds);
	// assertTrue(interesteds.size() == 0);
	// }
}
