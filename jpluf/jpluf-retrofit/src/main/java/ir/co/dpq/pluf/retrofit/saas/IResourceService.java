package ir.co.dpq.pluf.retrofit.saas;

import java.util.Map;

import retrofit.Callback;
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

public interface IResourceService {

	@Multipart
	@POST("/api/saas/app/{appId}/resource/create")
	RResource create(@Path("appId") Long appId, @Part("file") TypedFile file, @Part("description") String description);

	@Multipart
	@POST("/api/saas/app/{appId}/resource/create")
	void create(@Path("appId") Long appId, @Part("file") TypedFile file, @Part("description") String description,
			Callback<RResource> callback);

	@GET("/api/saas/app/{appId}/resource/{resourceId}")
	RResource get(@Path("appId") Long appId, @Path("resourceId") Long resourceId);

	@GET("/api/saas/app/{appId}/resource/{resourceId}")
	void get(@Path("appId") Long appId, @Path("resourceId") Long resourceId, Callback<RResource> callback);

	@FormUrlEncoded
	@POST("/api/saas/app/{appId}/resource/{resourceId}")
	RResource update(@Path("appId") Long appId, @Path("resourceId") Long resourceId,
			@FieldMap Map<String, Object> param);

	@FormUrlEncoded
	@POST("/api/saas/app/{appId}/resource/{resourceId}")
	void update(@Path("appId") Long appId, @Path("resourceId") Long resourceId, @FieldMap Map<String, Object> param,
			Callback<RResource> callback);

	@DELETE("/api/saas/app/{appId}/resource/{resourceId}")
	RResource delete(@Path("appId") Long appId, @Path("resourceId") Long resourceId);

	@DELETE("/api/saas/app/{appId}/resource/{resourceId}")
	void delete(@Path("appId") Long appId, @Path("resourceId") Long resourceId, Callback<RResource> callback);

	@GET("/api/saas/app/{appId}/resource/find")
	RResourcePaginatorPage find(@Path("appId") Long appId, @QueryMap Map<String, Object> param);

	@GET("/api/saas/app/{appId}/resource/find")
	void find(@Path("appId") Long appId, @QueryMap Map<String, Object> param,
			Callback<RResourcePaginatorPage> callback);
}
