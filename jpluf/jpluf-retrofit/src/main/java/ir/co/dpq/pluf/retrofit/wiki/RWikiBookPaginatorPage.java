package ir.co.dpq.pluf.retrofit.wiki;

import java.util.List;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.retrofit.RPaginatorPage;
import ir.co.dpq.pluf.wiki.PWikiBook;

public class RWikiBookPaginatorPage extends RPaginatorPage implements IPPaginatorPage<PWikiBook> {
	
	List<PWikiBook> items;

	@Override
	public boolean isEmpty() {
		return items == null || items.isEmpty();
	}

	@Override
	public List<PWikiBook> getItems() {
		return items;
	}

}
