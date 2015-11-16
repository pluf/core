package ir.co.dpq.pluf;

import java.util.List;

import ir.co.dpq.pluf.wiki.PWikiBook;

/**
 * 
 * @author maso
 *
 */
public class PPaginatedWikiBook implements IPPaginatorPage<PWikiBook> {

	private List<PWikiBook> items;
	private int pageNumber;
	private int itemsPerPage;
	private int currentPage;

	@Override
	public boolean isEmpty() {
		return getCounts() == 0;
	}

	@Override
	public int getCounts() {
		if (items == null)
			return 0;
		return items.size();
	}

	@Override
	public int getCurrentPage() {
		return this.currentPage;
	}

	public PPaginatedWikiBook setCurrentPage(int page) {
		this.currentPage = page;
		return this;
	}

	@Override
	public int getItemsPerPage() {
		return this.itemsPerPage;
	}

	public PPaginatedWikiBook setItemsPerPage(int itemPerPage) {
		this.itemsPerPage = itemPerPage;
		return this;
	}

	@Override
	public int getPageNumber() {
		return pageNumber;
	}

	public PPaginatedWikiBook setPageNumber(int i) {
		this.pageNumber = i;
		return this;

	}

	@Override
	public List<PWikiBook> getItems() {
		return this.items;
	}

	public PPaginatedWikiBook setItems(List<PWikiBook> list) {
		this.items = list;
		return this;
	}
}
