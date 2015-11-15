package ir.co.dpq.pluf.retrofit.saas;

import java.util.Map;

import ir.co.dpq.pluf.IPPaginatorPage;
import retrofit.http.DELETE;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.Multipart;
import retrofit.http.POST;
import retrofit.http.Part;
import retrofit.http.Path;
import retrofit.http.QueryMap;
import retrofit.mime.TypedFile;

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
	IPPaginatorPage<PTenant> findTenant(@QueryMap Map<String, Object> params);

	@GET("/api/saas/app/userList")
	IPPaginatorPage<PTenant> findUserTenant(@QueryMap Map<String, Object> params);

	@Multipart
	@POST("/api/saas/app/{appId}/resource/create")
	PResource createResource(@Path("appId") Long id, @Part("file") TypedFile file, @Part("description") String description);
}
