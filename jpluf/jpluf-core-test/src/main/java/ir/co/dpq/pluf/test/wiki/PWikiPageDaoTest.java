package ir.co.dpq.pluf.test.wiki;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.wiki.IPWikiPageDao;
import ir.co.dpq.pluf.wiki.PWikiPage;

public abstract class PWikiPageDaoTest {

	private IPWikiPageDao wikiService;

	@Before
	public void createService() {
		wikiService = getWikiPageInstance();
	}

	/**
	 * ایجاد یک نمونه از سرویس
	 * 
	 * سایر پیاده سازی‌ها با بازنویسی این فراخوانی و ایجاد سرویس می‌توانند از
	 * این تست‌ها استفاده کنند.
	 * 
	 * @return
	 */
	protected abstract IPWikiPageDao getWikiPageInstance();

	@Test
	public void createPageTest00() {
		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page);
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());
	}

	@Test
	public void getPageTest00() {
		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page);
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
		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page);
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.deleteWikiPage(cpage);
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());
	}

	@Test(expected = PException.class)
	public void deletePageTest01() {
		// create page
		PWikiPage page = new PWikiPage();
		page.setTitle("example");
		page.setSummary("summary");
		page.setContent("Content");
		page.setContentType("text/plain");

		PWikiPage cpage = wikiService.createWikiPage(page);
		assertNotNull(cpage);
		assertEquals(page.getSummary(), cpage.getSummary());
		assertEquals(page.getContent(), cpage.getContent());
		assertEquals(page.getContentType(), cpage.getContentType());

		PWikiPage cpage2 = wikiService.deleteWikiPage(cpage);
		assertNotNull(cpage2);
		assertEquals(page.getSummary(), cpage2.getSummary());
		assertEquals(page.getContent(), cpage2.getContent());
		assertEquals(page.getContentType(), cpage2.getContentType());

		wikiService.deleteWikiPage(cpage);
	}

	@Test
	public void findPageTest00() {
		PPaginatorParameter param = new PPaginatorParameter();
		IPPaginatorPage<PWikiPage> list = wikiService.findWikiPage(param);
		assertNotNull(list);
		assertNotNull(list.getItems());
	}
	//
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
	// wikiService.addLabelToPage(cpage.getId(), clabel.getId());
	// }
	//
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
	// wikiService.addLabelToPage(cpage.getId(), clabel.getId());
	//
	// Map<String, PLabel> labels = wikiService.getPageLabels(cpage.getId());
	// assertNotNull(labels);
	// assertTrue(labels.size() == 1);
	//
	// wikiService.deleteLabelFromPage(cpage.getId(), clabel.getId());
	// labels = wikiService.getPageLabels(cpage.getId());
	// assertNotNull(labels);
	// assertTrue(labels.size() == 0);
	//
	// }
	//
	// @Test
	// public void getLabelTest00() {
	//
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
	// wikiService.addLabelToPage(cpage.getId(), clabel.getId());
	//
	// Map<String, PLabel> labels = wikiService.getPageLabels(cpage.getId());
	// assertNotNull(labels);
	// assertTrue(labels.size() == 1);
	//
	// PLabel[] items = labels.values().toArray(new PLabel[0]);
	// assertEquals(clabel.getId(), items[0].getId());
	// }
	//
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
	// wikiService.addCategoryToPage(cpage.getId(), category2.getId());
	// }
	//
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
	// wikiService.addCategoryToPage(cpage.getId(), category2.getId());
	//
	// Map<String, PCategory> cats =
	// wikiService.getPageCategories(cpage.getId());
	// assertNotNull(cats);
	// assertTrue(cats.size() == 1);
	// }
	//
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
	// wikiService.addCategoryToPage(cpage.getId(), category2.getId());
	//
	// Map<String, PCategory> cats =
	// wikiService.getPageCategories(cpage.getId());
	// assertNotNull(cats);
	// assertTrue(cats.size() == 1);
	//
	// wikiService.deleteCategoryFromPage(cpage.getId(), category2.getId());
	// cats = wikiService.getPageCategories(cpage.getId());
	// assertNotNull(cats);
	// assertTrue(cats.size() == 0);
	// }
}
