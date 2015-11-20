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

/**
 * 
 * @author maso
 *
 */
public class PUserDaoRetrofitTest extends PUserDaoTest {

	private IRUserService userSerivece;
	private PUserDaoRetrofit userDao;

	public PUserDaoRetrofitTest() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.setDateFormat("yyyy-MM-dd HH:mm:ss");//
		Gson gson = gsonBuilder.create();

		RestAdapter restAdapter = new RestAdapter.Builder()//
				.setConverter(new GsonConverter(gson))//
				.setErrorHandler(new PErrorHandler())//
				.setEndpoint(API_URL)//
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
