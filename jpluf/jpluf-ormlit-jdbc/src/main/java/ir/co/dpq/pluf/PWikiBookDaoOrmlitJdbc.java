package ir.co.dpq.pluf;

import java.sql.SQLException;
import java.util.List;

import com.j256.ormlite.dao.Dao;

import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.IPWikiBookDao;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;
import ir.co.dpq.pluf.wiki.PWikiPageItem;

public class PWikiBookDaoOrmlitJdbc implements IPWikiBookDao {

	private Dao<PWikiBook, Long> wikiDao;

	@Override
	public PWikiBook createWikiBook(PWikiBook book) {
		try {
			if (book.getId() == null || book.getId() == 0) {
				book.setId(System.currentTimeMillis());
			}
			PWikiBook rbook = wikiDao.createIfNotExists(book);
			return rbook;
		} catch (SQLException e) {
			throw new PException("error", e);
		}
	}

	@Override
	public PWikiBook getWikiBook(Long bookId) {
		try {
			PWikiBook rbook = wikiDao.queryForId(bookId);
			return rbook;
		} catch (SQLException e) {
			throw new PException("error", e);
		}
	}

	@Override
	public PWikiBook updateWikiBook(PWikiBook book) {
		try {
			wikiDao.update(book);
			return book;
		} catch (SQLException e) {
			throw new PException("error", e);
		}
	}

	@Override
	public PWikiBook deleteWikiBook(PWikiBook book) {
		try {
			wikiDao.delete(book);
			book.setId(0l);
			return book;
		} catch (SQLException e) {
			throw new PException("error", e);
		}
	}

	@Override
	public IPPaginatorPage<PWikiBook> findWikiBook(PPaginatorParameter param) {
		// TODO Auto-generated method stub
		return null;
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

	public void setWikiDao(Dao<PWikiBook, Long> wikiDao) {
		this.wikiDao = wikiDao;
	}

}
