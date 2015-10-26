package ir.co.dpq.pluf.km;

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
 * دسترسی و دستکاری دسته‌ها
 * 
 * @author maso
 *
 */
public interface IPCategoryService {

	@FormUrlEncoded
	@POST("/api/km/category/create")
	PCategory createLabel(@FieldMap Map<String, Object> params);

	@GET("/api/km/category/{categoryId}")
	PCategory getLabel(@Path("categoryId") long id);
	
	@FormUrlEncoded
	@POST("/api/km/category/{categoryId}")
	PCategory createLabel(@FieldMap Map<String, Object> params, @Path("categoryId") long id);
	
	@DELETE("/api/km/category/{categoryId}")
	PCategory deleteLabel(@Path("categoryId") long id);
	
	@GET("/api/km/category/find")
	PPaginatorPage<PCategory> findWikiPage(@QueryMap Map<String, Object> params);
}
