package ir.co.dpq.pluf.tuser;

import static ir.co.dpq.pluf.TestConstant.API_URL;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import ir.co.dpq.pluf.PUserDaoRetrofit;
import ir.co.dpq.pluf.retrofit.PErrorHandler;
import ir.co.dpq.pluf.retrofit.user.IRUserService;
import ir.co.dpq.pluf.test.user.PUserDaoTest;
import ir.co.dpq.pluf.user.IPUserDao;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

public class PUserDaoRetrofitTest extends PUserDaoTest {

	private IRUserService userSerivece;
	private PUserDaoRetrofit userDao;

	public PUserDaoRetrofitTest() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.setDateFormat("yyyy-MM-dd HH:mm:ss")
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<PCategory>>() {
				// }.getType(), new DeserializerJson<PCategory>())//
				// .registerTypeAdapter(new TypeToken<PPaginatorPage<PLabel>>()
				// {
				// }.getType(), new DeserializerJson<PLabel>())//
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<PWikiPage>>() {
				// }.getType(), new DeserializerJson<PWikiPage>())//
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<RWikiPageItem>>() {
				// }.getType(), new DeserializerJson<RWikiPageItem>())//
				// .registerTypeAdapter(new
				// TypeToken<PPaginatorPage<RWikiBook>>() {
				// }.getType(), new DeserializerJson<RWikiBook>());
		;//
		Gson gson = gsonBuilder.create();

		RestAdapter restAdapter = new RestAdapter.Builder()//
				.setConverter(new GsonConverter(gson))//
				// تعیین کنترل کننده خطا
				.setErrorHandler(new PErrorHandler())
				// تعیین آدرس سایت مورد نظر
				.setEndpoint(API_URL)
				// ایجاد یک نمونه
				.build();
		this.userSerivece = restAdapter.create(IRUserService.class);
		
		userDao = new PUserDaoRetrofit();
		userDao.setUserService(userSerivece);
	}

	@Override
	protected IPUserDao getUserDaoInstance() {
		return userDao;
	}
}
