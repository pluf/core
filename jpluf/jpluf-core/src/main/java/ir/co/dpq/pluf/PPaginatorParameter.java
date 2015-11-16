package ir.co.dpq.pluf;

public class PPaginatorParameter {

	/**
	 * یک کویری است که انتظار داریم روی داده‌ها اجرا شود
	 */
	String query;

	/**
	 * صفحه جاری را تعیین می‌کند.
	 */
	int page;
	/**
	 * کلیدی را تعیین می‌کند که مرتب سازی باید بر اساس آن انجام شود
	 */
	String sortKey;

	/**
	 * ترتیب مرتب سازی را تعیین می‌کند.
	 */
	PSortOrder sortOrder;

	/**
	 * کلید فیلتر کردن را تعیین می‌کند.
	 */
	String filterKey;

	/**
	 * مقدار فیلتر کرد را
	 */
	String filterValue;

	public PPaginatorParameter() {
	}

	public PPaginatorParameter(PPaginatorParameter param) {
		setQuery(param.getQuery());
		setFilter(param.getFilterKey(), param.getFilterValue());
		setPage(param.getPage());
		setSortKey(param.getSortKey());
		setSortOrder(param.getSortOrder());
	}

	public String getQuery() {
		return query;
	}

	public void setQuery(String query) {
		this.query = query;
	}

	public int getPage() {
		return page;
	}

	public void setPage(int page) {
		this.page = page;
	}

	public String getSortKey() {
		return sortKey;
	}

	public void setSortKey(String sortKey) {
		this.sortKey = sortKey;
	}

	public PSortOrder getSortOrder() {
		if (sortOrder == null)
			return PSortOrder.desc;
		return sortOrder;
	}

	public void setSortOrder(PSortOrder sortOrder) {
		this.sortOrder = sortOrder;
	}

	public String getFilterKey() {
		return filterKey;
	}

	public void setFilterKey(String filterKey) {
		this.filterKey = filterKey;
	}

	public String getFilterValue() {
		return filterValue;
	}

	public void setFilterValue(String filterValue) {
		this.filterValue = filterValue;
	}

	/**
	 * فیلتر مورد نیاز را تعیین می‌کند.
	 * 
	 * @param key
	 * @param value
	 */
	public void setFilter(String key, String value) {
		setFilterKey(key);
		setFilterValue(value);
	}

}
