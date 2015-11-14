package ir.co.dpq.pluf;

import java.util.List;

public interface IPPaginatorPage<T> {

	boolean isEmpty();

	int getCounts();

	int getCurrentPage();

	int getItemsPerPage();

	int getPageNumber();

	List<T> getItems();

}
