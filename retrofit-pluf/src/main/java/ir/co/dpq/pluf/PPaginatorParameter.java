package ir.co.dpq.pluf;

import java.util.HashMap;
import java.util.Map;

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

	public Map<String, Object> toMap() {
		return this.map();
	}

	public Map<String, Object> map() {
		Map<String, Object> pmap = new HashMap<String, Object>();
		// _px_q
		if (getQuery() != null)
			pmap.put("_px_q", getQuery());
		// _px_p
		if (getPage() >= 0)
			pmap.put("_px_p", getPage());
		// _px_sk
		if (getSortKey() != null) {
			pmap.put("_px_sk", getSortKey());
			// _px_so
			switch (getSortOrder()) {
			case desc:
				pmap.put("_px_so", "d");
				break;
			case asc:
			default:
				pmap.put("_px_so", "a");
			}
		}
		// _px_fk
		if (getFilterKey() != null) {
			pmap.put("_px_fk", getFilterKey());
			// _px_fv
			if (getFilterValue() == null) {
				throw new PException("filter value is empty while key is set.");
			}
			pmap.put("_px_fv", getFilterValue());
		}
		return pmap;
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
