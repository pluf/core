package ir.co.dpq.pluf;

import java.util.List;

import ir.co.dpq.pluf.wiki.PWikiPageItem;

/**
 * 
 * @author maso
 *
 */
public class PPaginatedWikiItemPage extends PAbstractPaginatedPage implements IPPaginatorPage<PWikiPageItem> {

	private List<? extends PWikiPageItem> items;

	@Override
	public List<PWikiPageItem> getItems() {
		return (List<PWikiPageItem>) this.items;
	}

	public PPaginatedWikiItemPage setItems(List<? extends PWikiPageItem> list) {
		this.items = list;
		return this;
	}
}
