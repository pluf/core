package ir.co.dpq.pluf.retrofit.saas;

import java.util.Map;

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

	@GET("/api/saas/app/{appId}/resouce/{resourceId}")
	RResource get(@Path("appId") Long appId, @Path("resourceId") Long resourceId);

	@FormUrlEncoded
	@POST("/api/saas/app/{appId}/resouce/{resourceId}")
	RResource update(@Path("appId") Long appId, @Path("resourceId") Long resourceId,
			@FieldMap Map<String, Object> param);

	@DELETE("/api/saas/app/{appId}/resouce/{resourceId}")
	RResource delete(@Path("appId") Long appId, @Path("resourceId") Long resourceId);

	@GET("/api/saas/app/{appId}/resouce/find")
	RResourcePaginatorPage find(@Path("appId") Long appId, @QueryMap Map<String, Object> param);
}
