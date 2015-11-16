package ir.co.dpq.pluf.retrofit;

import com.google.gson.annotations.SerializedName;

public abstract class RPaginatorPage {

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

}
