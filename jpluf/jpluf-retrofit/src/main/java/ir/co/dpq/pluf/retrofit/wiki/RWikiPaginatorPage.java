package ir.co.dpq.pluf.retrofit.wiki;

import java.util.List;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.retrofit.RPaginatorPage;
import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * 
 * @author maso
 *
 */
public class RWikiPaginatorPage extends RPaginatorPage implements IPPaginatorPage<PWikiPage> {

	List<PWikiPage> items;

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
	public List<PWikiPage> getItems() {
		return items;
	}

}
