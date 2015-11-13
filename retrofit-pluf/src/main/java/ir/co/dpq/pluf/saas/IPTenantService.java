package ir.co.dpq.pluf.saas;

import java.util.Map;

import ir.co.dpq.pluf.PPaginatorPage;
import retrofit.http.DELETE;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;
import retrofit.http.QueryMap;

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public interface IPTenantService {

	@FormUrlEncoded
	@POST("/api/saas/app/create")
	PTenant createTenant(@FieldMap Map<String, Object> properties);

	@GET("/api/saas/app/{appId}")
	PTenant getTenant(@Path("appId") Long id);

	@GET("/api/saas/app")
	PTenant getTenant();

	@FormUrlEncoded
	@POST("/api/saas/app/{appId}")
	PTenant updateTenant(@Path("appId") Long id, @FieldMap Map<String, Object> map);

	@DELETE("/api/saas/app/{appId}")
	PTenant deleteTenant(@Path("appId") Long id);

	@GET("/api/saas/app/list")
	PPaginatorPage<PTenant> findTenant(@QueryMap Map<String, Object> params);

}
