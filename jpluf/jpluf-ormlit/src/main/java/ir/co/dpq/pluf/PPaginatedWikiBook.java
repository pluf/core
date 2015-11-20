package ir.co.dpq.pluf;

import java.util.List;

import ir.co.dpq.pluf.wiki.PWikiBook;

/**
 * 
 * @author maso
 *
 */
public class PPaginatedWikiBook extends PAbstractPaginatedPage implements IPPaginatorPage<PWikiBook> {

	private List<PWikiBook> items;

	@Override
	public List<PWikiBook> getItems() {
		return this.items;
	}

	public PPaginatedWikiBook setItems(List<PWikiBook> list) {
		this.items = list;
		return this;
	}
}
