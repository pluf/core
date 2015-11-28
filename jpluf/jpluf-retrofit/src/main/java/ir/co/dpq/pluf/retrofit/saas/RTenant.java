package ir.co.dpq.pluf.retrofit.saas;

import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.retrofit.IRObject;
import ir.co.dpq.pluf.saas.PTenant;

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class RTenant extends PTenant implements IRObject {

	@SerializedName("access_count")
	Long accessCount;

	/*
	 * (non-Javadoc)
	 * @see ir.co.dpq.pluf.saas.PTenant#getAccessCount()
	 */
	public Long getAccessCount() {
		return accessCount;
	}

	/*
	 * (non-Javadoc)
	 * @see ir.co.dpq.pluf.saas.PTenant#setAccessCount(java.lang.Long)
	 */
	public void setAccessCount(Long accessCount) {
		this.accessCount = accessCount;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.retrofit.IRObject#toMap()
	 */
	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("title", getTitle());
		map.put("description", getDescription());

		return map;
	}
}
