package ir.co.dpq.pluf;

import java.util.List;

import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * 
 * @author maso
 *
 */
public class PPaginatedWikiPage extends PAbstractPaginatedPage implements IPPaginatorPage<PWikiPage> {

	private List<PWikiPage> items;

	@Override
	public List<PWikiPage> getItems() {
		return this.items;
	}

	public PPaginatedWikiPage setItems(List<PWikiPage> list) {
		this.items = list;
		return this;
	}
}
