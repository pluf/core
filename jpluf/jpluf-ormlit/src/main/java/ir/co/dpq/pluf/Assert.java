package ir.co.dpq.pluf;

/**
 * 
 * @author maso
 *
 */
public class Assert {

	public static void assertNotNull(Object object, String message) {
		if (object == null) {
			throw new OrmLiteException(message);
		}
	}

}
