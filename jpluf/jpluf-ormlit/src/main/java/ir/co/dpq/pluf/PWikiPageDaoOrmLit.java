package ir.co.dpq.pluf;

import java.sql.SQLException;
import java.util.List;

import com.j256.ormlite.dao.Dao;

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

	@Override
	public PWikiPage getWikiPage(Long id) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public PWikiPage deleteWikiPage(PWikiPage page) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public IPPaginatorPage<PWikiPage> findWikiPage(PPaginatorParameter param) {
		// TODO Auto-generated method stub
		return null;
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
