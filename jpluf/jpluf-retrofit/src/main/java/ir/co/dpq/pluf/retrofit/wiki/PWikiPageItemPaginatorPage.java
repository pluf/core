package ir.co.dpq.pluf.retrofit.wiki;

import java.util.List;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.retrofit.RPaginatorPage;
import ir.co.dpq.pluf.wiki.PWikiPageItem;

/**
 * 
 * @author maso
 *
 */
public class PWikiPageItemPaginatorPage extends RPaginatorPage implements IPPaginatorPage<PWikiPageItem> {

	List<PWikiPageItem> items;

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.IPPaginatorPage#isEmpty()
	 */
	@Override
	public boolean isEmpty() {
		return items == null || items.isEmpty();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.IPPaginatorPage#getItems()
	 */
	@Override
	public List<PWikiPageItem> getItems() {
		return items;
	}

	public void setItems(List<PWikiPageItem> items) {
		this.items = items;
		if (isEmpty())
			setCounts(0);
		else
			setCounts(items.size());
	}

}
