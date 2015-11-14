package ir.co.dpq.pluf.km;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;

/**
 * امکانات دسترسی به برچسب‌ها و دستکاری آنها را فراهم می‌کند.
 * 
 * @author maso
 *
 */
public interface IPLabelService {

	PLabel createLabel(PLabel label);

	PLabel getLabel(Long id);

	PLabel updateLabel(PLabel label);

	PLabel deleteLabel(PLabel label);

	IPPaginatorPage<PLabel> findLabel(PPaginatorParameter params);
}
