/**
 * 
 */
package ir.co.dpq.pluf.retrofit;

import ir.co.dpq.pluf.PException;
import retrofit.ErrorHandler;
import retrofit.RetrofitError;

/**
 * مدیریت خطا سیستم.
 * 
 * تمام خطاهایی که از سمت کارخواه ارسال می‌شوند دارای ساختارهای داده‌ای خاصی
 * هستند و اطلاعات مناسبی را برای کاربر دارند. این پیام در بدنه برای کاربر ارسال
 * می‌شود. در اینجا این پیام دریافت شده و به ساختارهای مناسب تبدیل می‌شود.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class PErrorHandler implements ErrorHandler {

	/*
	 * (non-Javadoc)
	 * 
	 * @see retrofit.ErrorHandler#handleError(retrofit.RetrofitError)
	 */
	public Throwable handleError(RetrofitError cause) {
		// TODO:maso, 1394: بدنه پیام خوانده شود و پیام‌های مناسب در کلاس تعیین
		// شوند.
		PException ex = new PException(cause);
		return ex;
	}

}
