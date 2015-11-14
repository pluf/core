package ir.co.dpq.pluf;

public interface IPCallback<T> {

	void success(T t);

	void failure(PException exception);
}
