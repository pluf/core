package ir.co.dpq.pluf.inbox;

import ir.co.dpq.pluf.IPCallback;

/**
 * دسترسی به پیام‌های سیستمی را فراهم می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public interface IPSystemMessageService {

	void getAndDelete(IPCallback<PSystemMessage[]> callback);

	PSystemMessage[] getAndDelete();

	PSystemMessage newTestMessage();
}
