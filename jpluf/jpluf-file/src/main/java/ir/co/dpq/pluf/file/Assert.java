package ir.co.dpq.pluf.file;

import ir.co.dpq.pluf.PException;

public class Assert {

	public static void assertNotNull(Object object, String message) {
		if (object == null) {
			throw new PException(message);
		}
	}

}
