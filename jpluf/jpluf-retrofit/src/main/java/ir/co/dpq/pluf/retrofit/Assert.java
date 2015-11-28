package ir.co.dpq.pluf.retrofit;

public class Assert {

	public static void assertNotNull(Object object, String message) {
		if(object == null){
			throw new RuntimeException(message);
		}
	}

}
