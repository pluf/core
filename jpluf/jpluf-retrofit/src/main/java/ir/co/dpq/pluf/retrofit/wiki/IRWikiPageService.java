package ir.co.dpq.pluf.retrofit.wiki;

import java.util.Map;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import retrofit.Callback;
import retrofit.http.DELETE;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;
import retrofit.http.QueryMap;

/**
 * دسترسی به صفحه‌های ویکی را فراهم می‌کند
 *
 * صفحه‌های ویکی به عنوان یک منبع راهنما برای کاربران ایجاد شده است.
 * 
 * این واسط ابزارهای مورد نیاز برای دسترسی به این صفحه‌ها را فراهم کرده است.
 * 
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public interface IRWikiPageService {

	/**
	 * یک صفحه ویکی را بازیابی می‌کند.
	 * 
	 * @param lang
	 * @param pageId
	 * @param callback
	 */
	@GET("/api/wiki/{language}/{pageId}")
	void getWikiPage(@Path("language") String lang, @Path("pageId") String pageId, Callback<RWikiPage> callback);

	/**
	 * یک صفحه ویکی را بازیابی می‌کند
	 * 
	 * @see #getWikiPage(String, String, Callback)
	 * @param lang
	 * @param pageId
	 * @return
	 */
	@GET("/api/wiki/{language}/{pageId}")
	RWikiPage getWikiPage(@Path("language") String lang, @Path("pageId") String pageId);

	@FormUrlEncoded
	@POST("/api/wiki/page/create")
	RWikiPage createWikiPage(@FieldMap Map<String, Object> params);

	@GET("/api/wiki/page/{pageId}")
	RWikiPage getWikiPage(@Path("pageId") long id);

	@DELETE("/api/wiki/page/{pageId}")
	RWikiPage deleteWikiPage(@Path("pageId") long id);

	@GET("/api/wiki/page/find")
	IPPaginatorPage<RWikiPage> findWikiPage(@QueryMap Map<String, Object> params);

	@POST("/api/wiki/page/{pageId}/label/{labelId}")
	RWikiPage addLabelToPage(@Path("pageId") long pageId, @Path("labelId") long labelId);

	@GET("/api/wiki/page/{pageId}/labels")
	Map<String, PLabel> getPageLabels(@Path("pageId") long pageId);

	@DELETE("/api/wiki/page/{pageId}/label/{labelId}")
	RWikiPage deleteLabelFromPage(@Path("pageId") long pageId, @Path("labelId") long labelId);

	@POST("/api/wiki/page/{pageId}/category/{categoryId}")
	RWikiPage addCategoryToPage(@Path("pageId") long pageId, @Path("categoryId") long categoryId);

	@DELETE("/api/wiki/page/{pageId}/category/{categoryId}")
	RWikiPage deleteCategoryFromPage(@Path("pageId") long pageId, @Path("categoryId") long categoryId);

	@GET("/api/wiki/page/{pageId}/categories")
	Map<String, PCategory> getPageCategories(@Path("pageId") long pageId);

}
