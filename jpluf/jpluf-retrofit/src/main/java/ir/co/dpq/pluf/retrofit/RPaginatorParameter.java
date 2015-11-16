package ir.co.dpq.pluf.retrofit;

import java.util.HashMap;
import java.util.Map;

import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.PPaginatorParameter;

public class RPaginatorParameter extends PPaginatorParameter implements IRObject {

	public RPaginatorParameter() {
		super();
	}

	public RPaginatorParameter(PPaginatorParameter param) {
		super(param);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.retrofit.IRObject#toMap()
	 */
	public Map<String, Object> toMap() {
		Map<String, Object> pmap = new HashMap<String, Object>();
		// _px_q
		if (getQuery() != null)
			pmap.put("_px_q", getQuery());
		// _px_p
		if (getPage() >= 0)
			pmap.put("_px_p", getPage());
		// _px_sk
		if (getSortKey() != null) {
			pmap.put("_px_sk", getSortKey());
			// _px_so
			switch (getSortOrder()) {
			case desc:
				pmap.put("_px_so", "d");
				break;
			case asc:
			default:
				pmap.put("_px_so", "a");
			}
		}
		// _px_fk
		if (getFilterKey() != null) {
			pmap.put("_px_fk", getFilterKey());
			// _px_fv
			if (getFilterValue() == null) {
				throw new PException("filter value is empty while key is set.");
			}
			pmap.put("_px_fv", getFilterValue());
		}
		return pmap;
	}

}
