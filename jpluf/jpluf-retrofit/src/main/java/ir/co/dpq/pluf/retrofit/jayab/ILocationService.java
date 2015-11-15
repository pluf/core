package ir.co.dpq.pluf.retrofit.jayab;

import ir.co.dpq.pluf.IPPaginatorPage;

import java.util.Map;

import retrofit.Callback;
import retrofit.http.DELETE;
import retrofit.http.Field;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;
import retrofit.http.Query;
import retrofit.http.QueryMap;

public interface ILocationService {

	// *************************************************************************
	// Place Management
	// *************************************************************************

	// TODO: مستند نویسی:
	// دو متد اول برای فراخوانی بدون استفاده از paginator

	@GET("/api/jayab/location/find")
	void findLocation(@Query("latitude") double latitude, @Query("longitude") double longitude,
			@Query("count") Integer count, @Query("radius") Double radius, @Query("tag_key") String tagKey,
			@Query("tag_value") String tagValue, Callback<IPPaginatorPage<Location>> callBack);

	@GET("/api/jayab/location/find")
	IPPaginatorPage<Location> findLocation(@Query("latitude") double latitude, @Query("longitude") double longitude,
			@Query("count") Integer count, @Query("radius") Double radius, @Query("tag_key") String tagKey,
			@Query("tag_value") String tagValue);

	@GET("/api/jayab/location/find")
	void findLocation(@QueryMap Map<String, Object> params, Callback<IPPaginatorPage<Location>> callBack);

	@GET("/api/jayab/location/find")
	IPPaginatorPage<Location> findLocation(@QueryMap Map<String, Object> params);

	@GET("/api/jayab/location/list")
	void listLocations(@QueryMap Map<String, Object> params, Callback<IPPaginatorPage<Location>> callBack);

	@GET("/api/jayab/location/list")
	IPPaginatorPage<Location> listLocation(@QueryMap Map<String, Object> params);

	@FormUrlEncoded
	@POST("/api/jayab/location/create")
	void createLocation(@Field("latitude") double latitude, @Field("longitude") double longitude,
			@Field("name") String name, @Field("description") String desc, Callback<Location> callBack);

	@FormUrlEncoded
	@POST("/api/jayab/location/create")
	Location createLocation(@Field("latitude") double latitude, @Field("longitude") double longitude,
			@Field("name") String name, @Field("description") String desc);

	/**
	 * یک نمونه جدید از مکان را ایجاد می‌کند
	 * 
	 * پارامترهای این فراخوانی به صورت یک نگاشت تعیین می‌شود.
	 * 
	 * @see Location#map()
	 * @param params
	 * @param callBack
	 */
	@FormUrlEncoded
	@POST("/api/jayab/location/create")
	void createLocation(@FieldMap Map<String, Object> params, Callback<Location> callBack);

	/**
	 * یک مکان جدید را در سیستم ایجاد می‌کند.
	 * 
	 * @see #createLocation(Map, Callback)
	 * @param params
	 * @return
	 */
	@FormUrlEncoded
	@POST("/api/jayab/location/create")
	Location createLocation(@FieldMap Map<String, Object> params);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}")
	Location updateLocation(@Path("placeId") long placeId, @Field("latitude") Double latitude,
			@Field("longitude") Double longitude, @Field("name") String name, @Field("description") String desc);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}")
	void updateLocation(@Path("placeId") long placeId, @Field("latitude") Double latitude,
			@Field("longitude") Double longitude, @Field("name") String name, @Field("description") String desc,
			Callback<Location> callBack);

	/**
	 * یک مکان رو با استفاده از پارارمترها به روز می‌کند
	 * 
	 * @see #updateLocation(long, Map, Callback)
	 * @param placeId
	 * @param params
	 * @return
	 */
	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}")
	Location updateLocation(@Path("placeId") long placeId, @FieldMap Map<String, Object> params);

	/**
	 * یک مکان رو بر اساس پارامترهای ورودی به روز می‌کند.
	 * 
	 * فهرست پارامترهای برای به روز کردن به صورت یک نگاشت تعیین می‌شود.
	 * 
	 * @see Location#map()
	 * @param placeId
	 * @param params
	 * @param callBack
	 */
	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}")
	void updateLocation(@Path("placeId") long placeId, @FieldMap Map<String, Object> params,
			Callback<Location> callBack);

	@FormUrlEncoded
	@GET("/api/jayab/location/{placeId}")
	Location getLocation(@Path("placeId") long placeId);

	@FormUrlEncoded
	@GET("/api/jayab/location/{placeId}")
	void getLocation(@Path("placeId") long placeId, Callback<Location> callBack);

	@FormUrlEncoded
	@DELETE("/api/jayab/location/{placeId}")
	Location deleteLocation(@Path("placeId") long placeId);

	@FormUrlEncoded
	@DELETE("/api/jayab/location/{placeId}")
	void deleteLocation(@Path("placeId") long placeId, Callback<Location> callBack);

	// *************************************************************************
	// Tag Management
	// *************************************************************************

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/tag")
	void addTag(@Path("placeId") long placeId, @Field("tag_key") String tagKey, @Field("tag_value") String tagValue,
			Callback<Location> callBack);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/tag")
	Location addTag(@Path("placeId") long placeId, @Field("tag_key") String tagKey,
			@Field("tag_value") String tagValue);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/tag/{tagId}")
	void addTag(@Path("placeId") long placeId, @Path("tagId") long tagId, Callback<Location> callBack);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/tag/{tagId}")
	Location addTag(@Path("placeId") long placeId, @Path("tagId") long tagId);

	@FormUrlEncoded
	@DELETE("/api/jayab/location/{placeId}/tag")
	void deleteTag(@Path("placeId") long placeId, @Field("tag_key") String tagKey, @Field("tag_value") String tagValue,
			Callback<Location> callBack);

	@FormUrlEncoded
	@DELETE("/api/jayab/location/{placeId}/tag")
	Location deleteTag(@Path("placeId") long placeId, @Field("tag_key") String tagKey,
			@Field("tag_value") String tagValue);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/tag/{tagId}")
	void deleteTag(@Path("placeId") long placeId, @Path("tagId") long tagId, Callback<Location> callBack);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/tag/{tagId}")
	Location deleteTag(@Path("placeId") long placeId, @Path("tagId") long tagId);

	// *************************************************************************
	// Vote Management
	// *************************************************************************

	@GET("/api/jayab/location/{placeId}/votes")
	void getVoteSummary(@Path("placeId") long placeId, Callback<VoteSummary> callBack);

	@GET("/api/jayab/location/{placeId}/votes")
	VoteSummary getVoteSummary(@Path("placeId") long placeId);

	@GET("/api/jayab/location/{placeId}/vote")
	void getVote(@Path("placeId") long placeId, Callback<Vote> callBack);

	@GET("/api/jayab/location/{placeId}/vote")
	Vote getVoteState(@Path("placeId") long placeId);

	@DELETE("/api/jayab/location/{placeId}/vote")
	void deleteVote(@Path("placeId") long placeId, Callback<Vote> callBack);

	@DELETE("/api/jayab/location/{placeId}/vote")
	Vote deleteVote(@Path("placeId") long placeId);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/vote")
	void setVote(@Path("placeId") long placeId, @Field("like") boolean vote, Callback<Vote> callBack);

	@FormUrlEncoded
	@POST("/api/jayab/location/{placeId}/vote")
	Vote setVote(@Path("placeId") long placeId, @Field("like") boolean vote);

}
