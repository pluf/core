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
public interface IPLiberaryService {

	@FormUrlEncoded
	@POST("/api/saas/lib/create")
	PLibrary createLibrary(@FieldMap Map<String, Object> map);

	@GET("/api/saas/lib/{libId}")
	PLibrary getLibrary(@Path("libId") Long id);

	@FormUrlEncoded
	@POST("/api/saas/lib/{libId}")
	PLibrary updateLibrary(@Path("libId") Long id, @FieldMap Map<String, Object> map);

	@DELETE("/api/saas/lib/{libId}")
	PLibrary deleteLibrary(@Path("libId") Long id);

	@GET("/api/saas/lib/list")
	PPaginatorPage<PLibrary> findLibrary(@QueryMap Map<String, Object> params);

}
