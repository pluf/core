package ir.co.dpq.pluf;

import java.sql.SQLException;
import java.util.List;

import com.j256.ormlite.dao.Dao;
import com.j256.ormlite.stmt.PreparedQuery;
import com.j256.ormlite.stmt.QueryBuilder;

import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.wiki.IPWikiPageDao;
import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * 
 * @author maso
 *
 */
public class PWikiPageDaoOrmLit implements IPWikiPageDao {

	private Dao<PWikiPage, Long> wikiPageDao;

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * ir.co.dpq.pluf.wiki.IPWikiPageDao#createWikiPage(ir.co.dpq.pluf.wiki.
	 * PWikiPage)
	 */
	@Override
	public PWikiPage createWikiPage(PWikiPage page) {
		try {
			if (page.getId() == null || page.getId() == 0) {
				page.setId(System.currentTimeMillis());
			}
			PWikiPage rbook = wikiPageDao.createIfNotExists(page);
			return rbook;
		} catch (SQLException e) {
			throw new PException("error", e);
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.wiki.IPWikiPageDao#getWikiPage(java.lang.Long)
	 */
	@Override
	public PWikiPage getWikiPage(Long id) {
		try {
			PWikiPage rbook = wikiPageDao.queryForId(id);
			return rbook;
		} catch (SQLException e) {
			throw new PException("error", e);
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * ir.co.dpq.pluf.wiki.IPWikiPageDao#deleteWikiPage(ir.co.dpq.pluf.wiki.
	 * PWikiPage)
	 */
	@Override
	public PWikiPage deleteWikiPage(PWikiPage page) {
		try {
			PWikiPage tpage = getWikiPage(page.getId());
			Assert.assertNotNull(tpage, "page not found");
			wikiPageDao.delete(tpage);
			tpage.setId(0l);
			return tpage;
		} catch (SQLException e) {
			throw new PException("error", e);
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.wiki.IPWikiPageDao#findWikiPage(ir.co.dpq.pluf.
	 * PPaginatorParameter)
	 */
	@Override
	public IPPaginatorPage<PWikiPage> findWikiPage(PPaginatorParameter param) {
		try {
			// count
			QueryBuilder<PWikiPage, Long> queryBuilder = wikiPageDao.queryBuilder();
			Long count = queryBuilder.countOf();

			// Items
			queryBuilder = wikiPageDao.queryBuilder();
			queryBuilder//
					.limit((long) param.getItemPerPage())//
					.offset((long) param.getPage() * param.getItemPerPage());
			PreparedQuery<PWikiPage> preparedQuery = queryBuilder.prepare();
			List<PWikiPage> list = wikiPageDao.query(preparedQuery);

			PPaginatedWikiPage page = new PPaginatedWikiPage();
			page//
					.setItems(list)//
					.setItemsPerPage(param.getItemPerPage())//
					.setCurrentPage(param.getPage())//
					.setPageNumber(count.intValue() / param.getItemPerPage()
							+ ((count.intValue() % param.getItemPerPage() != 0) ? 1 : 0));
			return page;
		} catch (SQLException e) {
			throw new PException(e.getMessage(), e);
		}
	}

	@Override
	public PWikiPage addLabelToPage(PWikiPage page, PLabel label) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<PLabel> getPageLabels(PWikiPage page) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage deleteLabelFromPage(PWikiPage page, PLabel label) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage addCategoryToPage(PWikiPage page, PCategory category) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage deleteCategoryFromPage(PWikiPage page, PCategory category) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<PCategory> getPageCategories(PWikiPage page) {
		// TODO Auto-generated method stub
		return null;
	}

	public void setWikiPageDao(Dao<PWikiPage, Long> wikiPageDao) {
		this.wikiPageDao = wikiPageDao;
	}

}
