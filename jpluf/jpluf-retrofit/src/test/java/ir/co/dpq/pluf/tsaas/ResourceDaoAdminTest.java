package ir.co.dpq.pluf.tsaas;

import static ir.co.dpq.pluf.TestConstant.API_URL;
import static ir.co.dpq.pluf.test.TestCoreConstant.ADMIN_LOGIN;
import static ir.co.dpq.pluf.test.TestCoreConstant.ADMIN_PASSWORD;
import static org.junit.Assert.assertNotNull;

import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.CookiePolicy;

import org.junit.Before;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import ir.co.dpq.pluf.PResourceDaoRetrofit;
import ir.co.dpq.pluf.PUserDaoRetrofit;
import ir.co.dpq.pluf.retrofit.IRConfigurationService;
import ir.co.dpq.pluf.retrofit.PErrorHandler;
import ir.co.dpq.pluf.retrofit.saas.IResourceService;
import ir.co.dpq.pluf.retrofit.user.IRUserService;
import ir.co.dpq.pluf.saas.IPResourceDao;
import ir.co.dpq.pluf.saas.IPTenantDao;
import ir.co.dpq.pluf.saas.PTenant;
import ir.co.dpq.pluf.test.saas.PResourceDaoTest;
import ir.co.dpq.pluf.user.PUser;
import retrofit.RestAdapter;
import retrofit.converter.GsonConverter;

/**
 * 
 * @author maso
 *
 */
public class ResourceDaoAdminTest extends PResourceDaoTest {

	IRUserService userSerivece;
	PUserDaoRetrofit userDaoRetrofit;

	IResourceService resourceService;
	PResourceDaoRetrofit resourceDaoRetrofit;

	public ResourceDaoAdminTest() {
		CookieManager cookieManager = new CookieManager();
		cookieManager.setCookiePolicy(CookiePolicy.ACCEPT_ALL);
		CookieHandler.setDefault(cookieManager);

		GsonBuilder gsonBuilder = new GsonBuilder();
		gsonBuilder//
				.setDateFormat("yyyy-MM-dd HH:mm:ss");//
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
		this.userSerivece = restAdapter.create(IRUserService.class);
		this.resourceService = restAdapter.create(IResourceService.class);

		resourceDaoRetrofit = new PResourceDaoRetrofit();
		resourceDaoRetrofit.setResourceService(resourceService);
		resourceDaoRetrofit.setTenantDao(new IPTenantDao() {
			@Override
			public PTenant setCurrent(Long id) {
				return null;
			}

			@Override
			public PTenant get(Long id) {
				return null;
			}

			@Override
			public PTenant current() {
				PTenant tenant = new PTenant();
				tenant.setId(1l);
				tenant.setTitle("my app");
				return tenant;
			}
		});
		resourceDaoRetrofit.setConfigurationService(new IRConfigurationService() {
			@Override
			public String getEndpoint() {
				return API_URL;
			}
		});

		userDaoRetrofit = new PUserDaoRetrofit();
		userDaoRetrofit.setUserService(userSerivece);
	}

	@Before
	public void loginWithAdmin() {
		PUser user = userDaoRetrofit.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
	}

	@Override
	protected IPResourceDao getPResourceDao() {
		return resourceDaoRetrofit;
	}

}
