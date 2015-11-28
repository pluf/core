package ir.co.dpq.pluf.retrofit.saas;

import retrofit.http.Multipart;
import retrofit.http.POST;
import retrofit.http.Part;
import retrofit.http.Path;
import retrofit.mime.TypedFile;

public interface IResourceService {

	@Multipart
	@POST("/api/saas/app/{appId}/resource/create")
	RResource create(@Path("appId") Long appId, @Part("file") TypedFile file, @Part("description") String description);

}
