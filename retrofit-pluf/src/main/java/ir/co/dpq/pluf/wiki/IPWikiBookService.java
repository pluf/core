package ir.co.dpq.pluf.wiki;

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
 * کار با کتاب‌های ویکی
 * 
 * 
 * @author maso
 *
 */
public interface IPWikiBookService {

	@FormUrlEncoded
	@POST("/api/wiki/book/create")
	PWikiBook createWikiBook(@FieldMap Map<String, Object> map);

	@GET("/api/wiki/book/{bookId}")
	PWikiBook getWikiBook(@Path("bookId") long bookId);

	@FormUrlEncoded
	@POST("/api/wiki/book/{bookId}")
	PWikiBook updateWikiBook(@Path("bookId") long bookId, @FieldMap Map<String, Object> map);

	@DELETE("/api/wiki/book/{bookId}")
	PWikiBook deleteWikiBook(@Path("bookId") long id);

	@GET("/api/wiki/book/find")
	PPaginatorPage<PWikiPage> findWikiBook(@QueryMap Map<String, Object> params);

}
