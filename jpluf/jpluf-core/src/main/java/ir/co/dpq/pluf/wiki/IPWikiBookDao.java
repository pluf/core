package ir.co.dpq.pluf.wiki;

import java.util.List;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.km.PCategory;
import ir.co.dpq.pluf.km.PLabel;
import ir.co.dpq.pluf.user.PUser;

/**
 * کار با کتاب‌های ویکی
 * 
 * 
 * @author maso
 *
 */
public interface IPWikiBookDao {

	PWikiBook createWikiBook(PWikiBook book);

	PWikiBook getWikiBook(Long bookId);

	PWikiBook updateWikiBook(PWikiBook book);

	PWikiBook deleteWikiBook(PWikiBook book);

	IPPaginatorPage<PWikiBook> findWikiBook(PPaginatorParameter param);

	PWikiBook addLabelToBook(PWikiBook book, PLabel label);

	List<PLabel> getBookLabels(PWikiBook book);

	PWikiBook deleteLabelFromBook(PWikiBook book, PLabel label);

	PWikiPage addCategoryToBook(PWikiBook book, PCategory category);

	PWikiPage deleteCategoryFromBook(PWikiBook book, PCategory category);

	List<PCategory> getBookCategories(PWikiBook book);

	IPPaginatorPage<PWikiPageItem> getBookPages(PWikiBook book, PPaginatorParameter param);

	PWikiPage addPageToBook(PWikiBook book, PWikiPage page);

	PWikiPage deletePageFromBook(PWikiBook book, PWikiPage page);

	PWikiPage addInterestedUser(PWikiBook book);

	PWikiPage deleteInterestedUser(PWikiBook book);

	IPPaginatorPage<PUser> getBookInteresteds(PWikiBook book, PPaginatorParameter param);
}
