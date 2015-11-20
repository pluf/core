package ir.co.dpq.pluf;

import java.util.List;

import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.retrofit.Util;
import ir.co.dpq.pluf.retrofit.wiki.IRWikiPageService;
import ir.co.dpq.pluf.retrofit.wiki.RWikiPage;
import ir.co.dpq.pluf.wiki.IPWikiPageDao;
import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * 
 * @author maso
 *
 */
public class PWikiPageDaoRetrofit implements IPWikiPageDao {

	private IRWikiPageService wikiPageService;

	
	/*
	 * (non-Javadoc)
	 * @see ir.co.dpq.pluf.wiki.IPWikiPageDao#createWikiPage(ir.co.dpq.pluf.wiki.PWikiPage)
	 */
	@Override
	public PWikiPage createWikiPage(PWikiPage page) {
		RWikiPage rbook = Util.toRObject(page);
		return wikiPageService.createWikiPage(rbook.toMap());
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

	public void setWikiPageService(IRWikiPageService wikiPageService) {
		this.wikiPageService = wikiPageService;
	}

}
