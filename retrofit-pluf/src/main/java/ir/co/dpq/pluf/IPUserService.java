package ir.co.dpq.pluf;

import retrofit.Callback;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;

public interface IPUserService {

	@GET("/api/user/account")
	void getSessionUser(Callback<PUser> callback);

	@GET("/api/user/account")
	PUser getSessionUser();

	@FormUrlEncoded
	@POST("/api/user/account")
	void updateUserAccount(@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email,
			@Field("password") String password,
			@Field("language") String language,
			@Field("timezone") String timezone, Callback<PUser> callback);

	@FormUrlEncoded
	@POST("/api/user/account")
	PUser updateUserAccount(@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email,
			@Field("password") String password,
			@Field("language") String language,
			@Field("timezone") String timezone);

	@FormUrlEncoded
	@POST("/api/user/login")
	void login(@Field("login") String username,
			@Field("password") String password, Callback<PUser> callback);

	@FormUrlEncoded
	@POST("/api/user/login")
	PUser login(@Field("login") String username,
			@Field("password") String password);

	@GET("/api/user/logout")
	void logout(Callback<PUser> callback);

	@GET("/api/user/logout")
	PUser logout();

	@FormUrlEncoded
	@POST("/api/user/signup")
	void signup(@Field("login") String uername,
			@Field("password") String password,
			@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email,
			Callback<PUser> callBack);

	@FormUrlEncoded
	@POST("/api/user/signup")
	PUser signup(@Field("login") String uername,
			@Field("password") String password,
			@Field("first_name") String firstName,
			@Field("last_name") String lastName, @Field("email") String email);
}
