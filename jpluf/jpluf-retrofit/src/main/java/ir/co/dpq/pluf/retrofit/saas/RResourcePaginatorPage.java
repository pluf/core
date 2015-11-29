package ir.co.dpq.pluf.retrofit.saas;

import java.util.List;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PAbstractPaginatedPage;
import ir.co.dpq.pluf.saas.PResource;

/**
 * 
 * @author maso
 *
 */
public class RResourcePaginatorPage extends PAbstractPaginatedPage implements IPPaginatorPage<PResource> {

	List<PResource> items;

	public RResourcePaginatorPage() {
		// TODO Auto-generated constructor stub
	}

	public RResourcePaginatorPage(List<PResource> items) {
		setItems(items);
	}

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
	public List<PResource> getItems() {
		return items;
	}

	public void setItems(List<PResource> items) {
		this.items = items;
	}

}
