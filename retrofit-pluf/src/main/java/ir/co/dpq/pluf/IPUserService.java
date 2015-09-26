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
	@POST("/api/user/login")
	void login(
			@Field("login") String username,
			@Field("password") String password,
			Callback<PUser> callback);

	@FormUrlEncoded
	@POST("/api/user/login")
	PUser login(
			@Field("login") String username,
			@Field("password") String password);

	@GET("/api/user/logout")
	void logout(Callback<PUser> callback);
	
	@GET("/api/user/logout")
	PUser logout();
}
