package ir.co.dpq.pluf.inbox;

/**
 * ساختارهای داده برای پیام‌های سیستم را ایجاد می کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class PSystemMessage {

	long id;
	long user;
	String message;

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public long getUser() {
		return user;
	}

	public void setUser(long user) {
		this.user = user;
	}

	public String getMessage() {
		return message;
	}

	public void setMessage(String message) {
		this.message = message;
	}

}
