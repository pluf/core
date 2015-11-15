package ir.co.dpq.pluf;

import ir.co.dpq.pluf.IPCallback;
import ir.co.dpq.pluf.retrofit.Util;
import ir.co.dpq.pluf.retrofit.user.IRProfileAdministrator;
import ir.co.dpq.pluf.retrofit.user.RProfile;
import ir.co.dpq.pluf.user.IPProfileAdministrator;
import ir.co.dpq.pluf.user.PProfile;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * 
 * @author maso
 *
 */
public class PProfileAdministratorRetrofit implements IPProfileAdministrator {

	// @Inject
	IRProfileAdministrator profileAdministrator;

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * ir.co.dpq.pluf.user.IPProfileAdministrator#getProfile(java.lang.Long,
	 * ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void getProfile(Long id, final IPCallback<PProfile> callback) {
		profileAdministrator.getProfile(id, new Callback<RProfile>() {

			@Override
			public void success(RProfile t, Response response) {
				callback.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				// TODO: maso, 1394: convert exception
				callback.failure(new PException("fail to run request", error));
			}

		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * ir.co.dpq.pluf.user.IPProfileAdministrator#getProfile(java.lang.Long)
	 */
	@Override
	public PProfile getProfile(Long id) {
		return profileAdministrator.getProfile(id);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * ir.co.dpq.pluf.user.IPProfileAdministrator#updateProfile(ir.co.dpq.pluf.
	 * user.PProfile, ir.co.dpq.pluf.IPCallback)
	 */
	@Override
	public void updateProfile(PProfile profile, final IPCallback<PProfile> callback) {
		RProfile rprofile = Util.toRObject(profile);
		profileAdministrator.updateProfile(rprofile.getId(), rprofile.toMap(), new Callback<RProfile>() {

			@Override
			public void success(RProfile t, Response response) {
				callback.success(t);
			}

			@Override
			public void failure(RetrofitError error) {
				// TODO: maso, 1394: convert exception
				callback.failure(new PException("fail to run request", error));
			}

		});
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * ir.co.dpq.pluf.user.IPProfileAdministrator#updateProfile(ir.co.dpq.pluf.
	 * user.PProfile)
	 */
	@Override
	public PProfile updateProfile(PProfile profile) {
		RProfile rprofile = Util.toRObject(profile);
		return profileAdministrator.updateProfile(rprofile.getId(), rprofile.toMap());
	}

}
