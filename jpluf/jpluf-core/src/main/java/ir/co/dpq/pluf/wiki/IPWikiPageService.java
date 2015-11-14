package ir.co.dpq.pluf.wiki;

import java.util.List;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;

/**
 * دسترسی به صفحه‌های ویکی را فراهم می‌کند
 *
 * صفحه‌های ویکی به عنوان یک منبع راهنما برای کاربران ایجاد شده است.
 * 
 * این واسط ابزارهای مورد نیاز برای دسترسی به این صفحه‌ها را فراهم کرده است.
 * 
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public interface IPWikiPageService {

	PWikiPage createWikiPage(PWikiPage page);

	PWikiPage getWikiPage(Long id);

	PWikiPage deleteWikiPage(PWikiPage page);

	IPPaginatorPage<PWikiPage> findWikiPage(PPaginatorParameter param);

	PWikiPage addLabelToPage(PWikiPage page, PLabel label);

	List<PLabel> getPageLabels(PWikiPage page);

	PWikiPage deleteLabelFromPage(PWikiPage page, PLabel label);

	PWikiPage addCategoryToPage(PWikiPage page, PCategory category);
	
	PWikiPage deleteCategoryFromPage(PWikiPage page, PCategory category);

	List<PCategory> getPageCategories(PWikiPage page);

}
