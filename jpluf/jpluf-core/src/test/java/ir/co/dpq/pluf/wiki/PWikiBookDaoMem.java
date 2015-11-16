package ir.co.dpq.pluf.wiki;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.user.PUser;

public class PWikiBookDaoMem implements IPWikiBookDao {

	Map<Long, PWikiBook> storage;

	public PWikiBookDaoMem() {
		storage = new HashMap<Long, PWikiBook>();
	}

	@Override
	public synchronized PWikiBook createWikiBook(PWikiBook book) {
		PWikiBook cbook = new PWikiBook(book);
		cbook.setId(System.currentTimeMillis());
		cbook.setCreation(new Date());
		cbook.setModification(new Date());
		storage.put(cbook.getId(), cbook);
		return cbook;
	}

	@Override
	public PWikiBook getWikiBook(Long bookId) {
		return storage.get(bookId);
	}

	@Override
	public PWikiBook updateWikiBook(PWikiBook book) {
		PWikiBook cbook = getWikiBook(book.getId());
		cbook.update(book);
		cbook.setModification(new Date());
		return cbook;
	}

	@Override
	public PWikiBook deleteWikiBook(PWikiBook book) {
		PWikiBook cbook = getWikiBook(book.getId());
		this.storage.remove(book.getId());
		cbook.setId(0l);
		return cbook;
	}

	@Override
	public IPPaginatorPage<PWikiBook> findWikiBook(PPaginatorParameter param) {
		IPPaginatorPage<PWikiBook> page = new IPPaginatorPage<PWikiBook>(){

			@Override
			public boolean isEmpty() {
				return storage.isEmpty();
			}

			@Override
			public int getCounts() {
				return storage.size();
			}

			@Override
			public int getCurrentPage() {
				return 1;
			}

			@Override
			public int getItemsPerPage() {
				return  getCounts();
			}

			@Override
			public int getPageNumber() {
				return 1;
			}

			@Override
			public List<PWikiBook> getItems() {
				ArrayList<PWikiBook> books = new ArrayList<PWikiBook>();
				books.addAll(storage.values());
				return books;
			}
			
		};
		return page;
	}

	@Override
	public PWikiBook addLabelToBook(PWikiBook book, PLabel label) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<PLabel> getBookLabels(PWikiBook book) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiBook deleteLabelFromBook(PWikiBook book, PLabel label) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage addCategoryToBook(PWikiBook book, PCategory category) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage deleteCategoryFromBook(PWikiBook book, PCategory category) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<PCategory> getBookCategories(PWikiBook book) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public IPPaginatorPage<PWikiPageItem> getBookPages(PWikiBook book, PPaginatorParameter param) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage addPageToBook(PWikiBook book, PWikiPage page) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage deletePageFromBook(PWikiBook book, PWikiPage page) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage addInterestedUser(PWikiBook book) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage deleteInterestedUser(PWikiBook book) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public IPPaginatorPage<PUser> getBookInteresteds(PWikiBook book, PPaginatorParameter param) {
		// TODO Auto-generated method stub
		return null;
	}

}
