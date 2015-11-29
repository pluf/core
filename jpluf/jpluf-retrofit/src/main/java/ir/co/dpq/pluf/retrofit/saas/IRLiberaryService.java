package ir.co.dpq.pluf.retrofit.saas;

import java.util.Map;

import ir.co.dpq.pluf.IPPaginatorPage;
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
public interface IRLiberaryService {

	@FormUrlEncoded
	@POST("/api/saas/lib/create")
	RLibrary createLibrary(@FieldMap Map<String, Object> map);

	@GET("/api/saas/lib/{libId}")
	RLibrary getLibrary(@Path("libId") Long id);

	@FormUrlEncoded
	@POST("/api/saas/lib/{libId}")
	RLibrary updateLibrary(@Path("libId") Long id, @FieldMap Map<String, Object> map);

	@DELETE("/api/saas/lib/{libId}")
	RLibrary deleteLibrary(@Path("libId") Long id);

	@GET("/api/saas/lib/list")
	IPPaginatorPage<RLibrary> findLibrary(@QueryMap Map<String, Object> params);

}
