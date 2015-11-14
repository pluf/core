//package ir.co.dpq.pluf.tkm;
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
//import ir.co.dpq.pluf.retrofit.km.IPLabelService;
//import ir.co.dpq.pluf.retrofit.km.PLabel;
//import ir.co.dpq.pluf.retrofit.user.IPUserService;
//import ir.co.dpq.pluf.retrofit.user.PUser;
//import ir.co.dpq.pluf.retrofit.wiki.RWikiBook;
//import ir.co.dpq.pluf.retrofit.wiki.PWikiPage;
//import retrofit.RestAdapter;
//import retrofit.converter.GsonConverter;
//
//public class LabelServiceTest {
//
//	private IPLabelService labelService;
//	private IPUserService usr;
//
//	@Before
//	public void createService() {
//		CookieManager cookieManager = new CookieManager();
//		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
//		CookieHandler.setDefault(cookieManager);
//
//		GsonBuilder gsonBuilder = new GsonBuilder();
//		gsonBuilder//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<PLabel>>() {
//				}.getType(), new DeserializerJson<PLabel>())//
//				.registerTypeAdapter(new TypeToken<PPaginatorPage<PWikiPage>>() {
//				}.getType(), new DeserializerJson<PWikiPage>())//
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
//		this.labelService = restAdapter.create(IPLabelService.class);
//		this.usr = restAdapter.create(IPUserService.class);
//	}
//
//	@Test
//	public void createlabelTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		// create label
//		PLabel label = new PLabel();
//		label.setTitle("example");
//		label.setDescription("label description");
//		label.setColor("#FFFFFF");
//
//		PLabel clabel = labelService.createLabel(label.toMap());
//		assertNotNull(clabel);
//		assertEquals(label.getTitle(), clabel.getTitle());
//		assertEquals(label.getColor(), clabel.getColor());
//	}
//
//	@Test
//	public void getlabelTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		// create label
//		PLabel label = new PLabel();
//		label.setTitle("example");
//		label.setDescription("label description");
//		label.setColor("#FFFFFF");
//
//		PLabel clabel = labelService.createLabel(label.toMap());
//		assertNotNull(clabel);
//		assertEquals(label.getTitle(), clabel.getTitle());
//		assertEquals(label.getColor(), clabel.getColor());
//
//		PLabel clabel2 = labelService.getLabel(clabel.getId());
//		assertNotNull(clabel2);
//		assertEquals(label.getTitle(), clabel2.getTitle());
//		assertEquals(label.getColor(), clabel2.getColor());
//	}
//
//	@Test
//	public void updateLabelTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		// create label
//		PLabel label = new PLabel();
//		label.setTitle("example");
//		label.setDescription("label description");
//		label.setColor("#FFFFFF");
//
//		PLabel clabel = labelService.createLabel(label.toMap());
//		assertNotNull(clabel);
//		assertEquals(label.getTitle(), clabel.getTitle());
//		assertEquals(label.getColor(), clabel.getColor());
//
//		label.setTitle("example :"+Math.random());
//		label.setColor("#ffffff"+Math.random());
//		label.setDescription("descritpion " + Math.random());
//		
//		PLabel clabel2 = labelService.updateLabel(label.toMap(), clabel.getId());
//		assertNotNull(clabel2);
//		assertEquals(label.getTitle(), clabel2.getTitle());
//		assertEquals(label.getColor(), clabel2.getColor());
//	}
//
//	@Test
//	public void deletelabelTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		// create label
//		PLabel label = new PLabel();
//		label.setTitle("example");
//		label.setDescription("label description");
//		label.setColor("#FFFFFF");
//
//		PLabel clabel = labelService.createLabel(label.toMap());
//		assertNotNull(clabel);
//		assertEquals(label.getTitle(), clabel.getTitle());
//		assertEquals(label.getColor(), clabel.getColor());
//
//		PLabel clabel2 = labelService.deleteLabel(clabel.getId());
//		assertNotNull(clabel2);
//		assertEquals(label.getTitle(), clabel2.getTitle());
//		assertEquals(label.getColor(), clabel2.getColor());
//	}
//
//	@Test(expected = PException.class)
//	public void deletelabelTest01() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		// create label
//		PLabel label = new PLabel();
//		label.setTitle("example");
//		label.setDescription("label description");
//		label.setColor("#FFFFFF");
//
//		PLabel clabel = labelService.createLabel(label.toMap());
//		assertNotNull(clabel);
//		assertEquals(label.getTitle(), clabel.getTitle());
//		assertEquals(label.getColor(), clabel.getColor());
//
//		PLabel clabel2 = labelService.deleteLabel(clabel.getId());
//		assertNotNull(clabel2);
//		assertEquals(label.getTitle(), clabel2.getTitle());
//		assertEquals(label.getColor(), clabel2.getColor());
//
//		labelService.deleteLabel(clabel.getId());
//	}
//
//	@Test
//	public void findlabelTest00() {
//		// Login
//		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
//		assertNotNull(user);
//
//		// create label
//		PLabel label = new PLabel();
//		label.setTitle("example");
//		label.setDescription("label description");
//		label.setColor("#FFFFFF");
//
//		PLabel clabel = labelService.createLabel(label.toMap());
//		assertNotNull(clabel);
//
//		HashMap<String, Object> param = new HashMap<>();
//		PPaginatorPage<PLabel> list = labelService.findLabel(param);
//		assertNotNull(list);
//		assertNotNull(list.getItems());
//		assertTrue(list.getItems().size() > 0);
//	}
//}
