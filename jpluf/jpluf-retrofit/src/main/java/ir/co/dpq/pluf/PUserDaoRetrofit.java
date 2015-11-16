package ir.co.dpq.pluf;

import ir.co.dpq.pluf.retrofit.Util;
import ir.co.dpq.pluf.retrofit.user.IRUserService;
import ir.co.dpq.pluf.retrofit.user.RUser;
import ir.co.dpq.pluf.user.IPUserDao;
import ir.co.dpq.pluf.user.PUser;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * 
 * @author maso
 *
 */
public class PUserDaoRetrofit implements IPUserDao {

	private IRUserService userService;

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#login(java.lang.String,
	 * java.lang.String, ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void login(String username, String password, final IPCallback<PUser> callback) {
		userService.login(username, password, new Callback<RUser>() {

			@Override
			public void success(RUser t, Response response) {
				callback.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				callback.failure(new PException("", error));
			}
		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#login(java.lang.String,
	 * java.lang.String)
	 */
	@Override
	public PUser login(String username, String password) {
		return userService.login(username, password);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#logout(ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void logout(final IPCallback<PUser> callback) {
		userService.logout(new Callback<RUser>() {

			@Override
			public void success(RUser t, Response response) {
				callback.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				callback.failure(new PException("", error));
			}
		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#logout()
	 */
	@Override
	public PUser logout() {
		return userService.logout();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * ir.co.dpq.pluf.user.IPUserDao#getSessionUser(ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void getSessionUser(final IPCallback<PUser> callback) {
		userService.getSessionUser(new Callback<RUser>() {
			@Override
			public void success(RUser t, Response response) {
				callback.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				callback.failure(new PException("", error));
			}
		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#getSessionUser()
	 */
	@Override
	public PUser getSessionUser() {
		return userService.getSessionUser();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#getUserInfo(java.lang.Long,
	 * ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void getUserInfo(Long userId, final IPCallback<PUser> callback) {
		userService.getUserInfo(userId, new Callback<RUser>() {
			@Override
			public void success(RUser t, Response response) {
				callback.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				callback.failure(new PException("", error));
			}
		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#getUserInfo(java.lang.Long)
	 */
	@Override
	public PUser getUserInfo(Long userId) {
		return userService.getUserInfo(userId);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#update(ir.co.dpq.pluf.user.PUser,
	 * ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void update(PUser user, final IPCallback<PUser> callback) {
		RUser ruser = Util.toRObject(user);
		userService.update(ruser.toMap(), new Callback<RUser>() {

			@Override
			public void success(RUser t, Response response) {
				callback.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				callback.failure(new PException("", error));
			}
		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#update(ir.co.dpq.pluf.user.PUser)
	 */
	@Override
	public PUser update(PUser user) {
		RUser ruser = Util.toRObject(user);
		return userService.update(ruser.toMap());
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#signup(ir.co.dpq.pluf.user.PUser,
	 * ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void signup(PUser user, final IPCallback<PUser> callBack) {
		RUser ruser = Util.toRObject(user);
		userService.signup(ruser.toMap(), new Callback<RUser>() {
			@Override
			public void success(RUser t, Response response) {
				callBack.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				callBack.failure(new PException("", error));
			}
		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.IPUserDao#signup(ir.co.dpq.pluf.user.PUser)
	 */
	@Override
	public PUser signup(PUser user) {
		RUser ruser = Util.toRObject(user);
		return userService.signup(ruser.toMap());
	}

	public void setUserService(IRUserService userSerivece) {
		this.userService = userSerivece;
	}

}
