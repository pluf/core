package ir.co.dpq.pluf;

import java.util.List;

/**
 * 
 * @author maso
 *
 */
public abstract class PAbstractPaginatedPage {

	private int pageNumber;
	private int itemsPerPage;
	private int currentPage;

	public boolean isEmpty() {
		return getCounts() == 0;
	}

	public int getCounts() {
		if (getItems() == null)
			return 0;
		return getItems().size();
	}

	public int getCurrentPage() {
		return this.currentPage;
	}

	public PAbstractPaginatedPage setCurrentPage(int page) {
		this.currentPage = page;
		return this;
	}

	public int getItemsPerPage() {
		return this.itemsPerPage;
	}

	public PAbstractPaginatedPage setItemsPerPage(int itemPerPage) {
		this.itemsPerPage = itemPerPage;
		return this;
	}

	public int getPageNumber() {
		return pageNumber;
	}

	public PAbstractPaginatedPage setPageNumber(int i) {
		this.pageNumber = i;
		return this;

	}

	public abstract List<? extends Object> getItems();

}
