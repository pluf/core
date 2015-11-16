package ir.co.dpq.pluf;

import java.util.List;

import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.retrofit.RPaginatorParameter;
import ir.co.dpq.pluf.retrofit.Util;
import ir.co.dpq.pluf.retrofit.wiki.IRWikiBookService;
import ir.co.dpq.pluf.retrofit.wiki.RWikiBook;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiBookDao;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import ir.co.dpq.pluf.wiki.PWikiPageItem;

/**
 * 
 * @author maso
 *
 */
public class PWikiBookDaoRetrofit implements IPWikiBookDao {

	private IRWikiBookService wikiBookService;

	@Override
	public PWikiBook createWikiBook(PWikiBook book) {
		RWikiBook rbook = Util.toRObject(book);
		return wikiBookService.createWikiBook(rbook.toMap());
	}

	@Override
	public PWikiBook getWikiBook(Long bookId) {
		return this.wikiBookService.getWikiBook(bookId);
	}

	@Override
	public PWikiBook updateWikiBook(PWikiBook book) {
		RWikiBook rbook = Util.toRObject(book);
		return this.wikiBookService.updateWikiBook(rbook.getId(), rbook.toMap());
	}

	@Override
	public PWikiBook deleteWikiBook(PWikiBook book) {
		return this.wikiBookService.deleteWikiBook(book.getId());
	}

	@Override
	public IPPaginatorPage<PWikiBook> findWikiBook(PPaginatorParameter param) {
		RPaginatorParameter rparams = Util.toRObject(param);
		return wikiBookService.findWikiBook(rparams.toMap());
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

	public void setWikiBookService(IRWikiBookService wikiBookService) {
		this.wikiBookService = wikiBookService;
	}

}
