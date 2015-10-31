package ir.co.dpq.pluf.saas;

import java.util.HashMap;
import java.util.Map;

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class PLibrary {

	public static final int MODE_DEBUG = 1;
	public static final int MODE_RELESE = 2;

	public static final int TYPE_JAVASCRITP = 1;
	public static final int TYPE_CSS = 2;

	private Long id;
	private Integer mode;
	private Integer type;
	private String name;
	private String version;
	private String description;
	private String path;

	// Date creation_dtime;
	// Date modif_dtime;

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public Integer getMode() {
		return mode;
	}

	public void setMode(Integer mode) {
		this.mode = mode;
	}

	public Integer getType() {
		return type;
	}

	public void setType(Integer type) {
		this.type = type;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getVersion() {
		return version;
	}

	public void setVersion(String version) {
		this.version = version;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getPath() {
		return path;
	}

	public void setPath(String path) {
		this.path = path;
	}

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<>();

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
