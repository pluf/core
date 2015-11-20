/**
 * 
 */
package ir.co.dpq.pluf;

/**
 * @author maso
 *
 */
public class OrmLiteException extends PException {

	/**
	 * 
	 */
	private static final long serialVersionUID = 1774120905057143129L;

	/**
	 * 
	 */
	public OrmLiteException() {
		// TODO Auto-generated constructor stub
	}

	/**
	 * @param message
	 * @param cause
	 * @param enableSuppression
	 * @param writableStackTrace
	 */
	public OrmLiteException(String message, Throwable cause, boolean enableSuppression, boolean writableStackTrace) {
		super(message, cause, enableSuppression, writableStackTrace);
		// TODO Auto-generated constructor stub
	}

	/**
	 * @param message
	 * @param cause
	 */
	public OrmLiteException(String message, Throwable cause) {
		super(message, cause);
		// TODO Auto-generated constructor stub
	}

	/**
	 * @param message
	 */
	public OrmLiteException(String message) {
		super(message);
		// TODO Auto-generated constructor stub
	}

	/**
	 * @param cause
	 */
	public OrmLiteException(Throwable cause) {
		super(cause);
		// TODO Auto-generated constructor stub
	}

}
