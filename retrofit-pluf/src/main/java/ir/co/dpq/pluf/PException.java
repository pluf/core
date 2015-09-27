package ir.co.dpq.pluf;


/**
 * خطای پایه سیستم
 * 
 * تمام خطا‌هایی که در این بسته تولید می‌شوند همگی توسعه یافته از این کلاس هستند.
 * 
 * @author maso
 *
 */
public class PException extends RuntimeException {

	/**
	 * 
	 */
	private static final long serialVersionUID = 3947553243056003038L;

	/**
	 * 
	 */
	public PException() {
		super();
	}

	/**
	 * @param message
	 * @param cause
	 * @param enableSuppression
	 * @param writableStackTrace
	 */
	public PException(String message, Throwable cause, boolean enableSuppression, boolean writableStackTrace) {
		super(message, cause, enableSuppression, writableStackTrace);
	}

	/**
	 * @param message
	 * @param cause
	 */
	public PException(String message, Throwable cause) {
		super(message, cause);
	}

	/**
	 * @param message
	 */
	public PException(String message) {
		super(message);
	}

	/**
	 * @param cause
	 */
	public PException(Throwable cause) {
		super(cause);
	}
}
