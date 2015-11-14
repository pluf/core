package ir.co.dpq.pluf.retrofit;

import java.util.List;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.IPPaginatorPage;

public class PPaginatorPage<T> implements IPPaginatorPage<T> {

	/**
	 * تعداد گزینه‌های لیست را تعیین می‌کند
	 */
	int counts;

	/**
	 * اندیس صفحه جاری را تعیین می‌کند
	 */
	@SerializedName("current_p")
	int currentPage;

	/**
	 * تعداد گزینه‌های در صفحه را تعیین می‌کند
	 */
	@SerializedName("items_per_page")
	int itemsPerPage;

	/**
	 * تعداد کل صفحه‌ها را تعیینن می‌کند.
	 */
	@SerializedName("page_number")
	int pageNumber;
	
	@SerializedName("page_items")
	List<T> items;


	public boolean isEmpty() {
		return items == null || items.isEmpty();
	}

	public int getCounts() {
		return counts;
	}

	public void setCounts(int counts) {
		this.counts = counts;
	}

	public int getCurrentPage() {
		return currentPage;
	}

	public void setCurrentPage(int currentPage) {
		this.currentPage = currentPage;
	}

	public int getItemsPerPage() {
		return itemsPerPage;
	}

	public void setItemsPerPage(int itemsPerPage) {
		this.itemsPerPage = itemsPerPage;
	}

	public int getPageNumber() {
		return pageNumber;
	}

	public void setPageNumber(int pageNumber) {
		this.pageNumber = pageNumber;
	}

	public List<T> getItems() {
		return items;
	}

	public void setItems(List<T> items) {
		this.items = items;
	}

}
