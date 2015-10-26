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
 * امکانات دسترسی به برچسب‌ها و دستکاری آنها را فراهم می‌کند.
 * 
 * @author maso
 *
 */
public interface IPLabelService {

	@FormUrlEncoded
	@POST("/api/km/label/create")
	PLabel createLabel(@FieldMap Map<String, Object> params);

	@GET("/api/km/label/{labelId}")
	PLabel getLabel(@Path("labelId") long id);
	
	@FormUrlEncoded
	@POST("/api/km/label/{labelId}")
	PLabel updateLabel(@FieldMap Map<String, Object> params, @Path("labelId") long id);
	
	@DELETE("/api/km/label/{labelId}")
	PLabel deleteLabel(@Path("labelId") long id);
	
	@GET("/api/km/label/find")
	PPaginatorPage<PLabel> findLabel(@QueryMap Map<String, Object> params);
}
