package ir.co.dpq.pluf.retrofit.saas;

import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.retrofit.IRObject;
import ir.co.dpq.pluf.saas.PLibrary;

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class RLibrary extends PLibrary implements IRObject {

	@SerializedName("creation_dtime")
	Date creation;

	@SerializedName("modif_dtime")
	Date modification;

	public Date getCreation() {
		return creation;
	}

	public void setCreation(Date creation) {
		this.creation = creation;
	}

	public Date getModification() {
		return modification;
	}

	public void setModification(Date modification) {
		this.modification = modification;
	}

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("mode", getMode());
		map.put("type", getType());
		map.put("name", getName());
		map.put("version", getVersion());
		map.put("description", getDescription());
		map.put("path", getPath());

		return map;
	}

}
