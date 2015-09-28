package ir.co.dpq.pluf.inbox;

import retrofit.Callback;
import retrofit.http.GET;

/**
 * دسترسی به پیام‌های سیستمی را فراهم می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public interface IPSystemMessageService {

	@GET("/api/inbox/system/list")
	void getAndDelete(Callback<PSystemMessage[]> callback);

	@GET("/api/inbox/system/list")
	PSystemMessage[] getAndDelete();

	@GET("/api/inbox/system/test")
	PSystemMessage newTestMessage();
}
