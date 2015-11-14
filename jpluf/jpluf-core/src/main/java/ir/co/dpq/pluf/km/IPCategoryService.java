package ir.co.dpq.pluf.km;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;

/**
 * دسترسی و دستکاری دسته‌ها
 * 
 * @author maso
 *
 */
public interface IPCategoryService {

	/**
	 * یک دسته در ریشه ایجاد می‌کند.
	 * 
	 * @param params
	 * @return
	 */
	PCategory createCategory(PCategory category);

	/**
	 * یک دسته در دسته تعیین شده ایجاد می‌کند.
	 * 
	 * @param params
	 * @return
	 */
	PCategory createCategory(Long parentId, PCategory category);

	PCategory getRootCategory();

	PCategory getCategory(Long id);

	IPPaginatorPage<PCategory> getSubCategory(PCategory category, PPaginatorParameter param);

	PCategory updateCategory(PCategory category);

	PCategory deleteCategory(PCategory category);

	IPPaginatorPage<PCategory> findCategory(PPaginatorParameter param);
}
