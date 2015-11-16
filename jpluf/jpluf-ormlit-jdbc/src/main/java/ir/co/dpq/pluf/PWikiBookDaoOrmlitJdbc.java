package ir.co.dpq.pluf;

import java.sql.SQLException;
import java.util.List;

import com.j256.ormlite.dao.Dao;
import com.j256.ormlite.stmt.PreparedQuery;
import com.j256.ormlite.stmt.QueryBuilder;

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

		QueryBuilder<PWikiBook, Long> queryBuilder = wikiDao.queryBuilder();
		try {
			// count
			Long count = queryBuilder.countOf();

			// Items
			queryBuilder = wikiDao.queryBuilder();
			queryBuilder//
					.limit((long) param.getItemPerPage())//
					.offset((long) param.getPage() * param.getItemPerPage());
			PreparedQuery<PWikiBook> preparedQuery = queryBuilder.prepare();
			List<PWikiBook> list = wikiDao.query(preparedQuery);;

			PPaginatedWikiBook page = new PPaginatedWikiBook();
			page.setItemsPerPage(param.getItemPerPage())//
					.setItems(list)//
					.setCurrentPage(param.getPage())//
					.setPageNumber(count.intValue() / param.getItemPerPage()
							+ ((count.intValue() % param.getItemPerPage() != 0) ? 1 : 0));
			return page;
		} catch (SQLException e) {
			throw new PException(e.getMessage(), e);
		}

		//
		// Query query = null;
		// Query q = null;
		//
		// wikiDao.queryBuilder().where().
		// if (parameter.getQuery() != null &&
		// parameter.getQuery().trim().length() > 0) {
		// query = session.createQuery("FROM AuditLog a WHERE " //
		// + "(a.message like :query) OR " //
		// + "(a.subject like :query) OR " //
		// + "(a.object like :query)");
		// q = session.createQuery("SELECT COUNT(*) FROM AuditLog a WHERE " //
		// + "(a.message like :query) OR " //
		// + "(a.subject like :query) OR " //
		// + "(a.object like :query)");
		// query.setString("query", parameter.getQuery().trim());
		// q.setString("query", parameter.getQuery().trim());
		// } else {
		// query = session.createQuery("FROM AuditLog");
		// q = session.createQuery("SELECT COUNT(*) FROM AuditLog a");
		// }
		//
		// query.setFirstResult(parameter.getPageNumber() *
		// parameter.getItemsPerPage());
		// query.setMaxResults(parameter.getItemsPerPage());
		//
		// @SuppressWarnings("unchecked")
		// List<AuditLog> list = query.list();
		//
		//
		// PaginatedPage<AuditLog> page = new PaginatedPage<>(list);
		// page.setItemsPerPage(parameter.getItemsPerPage());
		// page.setCurrentPage(parameter.getPageNumber());
		// page.setPageNumber(count.intValue() / parameter.getItemsPerPage()
		// + ((count.intValue() % parameter.getItemsPerPage() != 0) ? 1 : 0));
		// return page;
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
