package ir.co.dpq.pluf.retrofit.km;

import java.util.HashMap;
import java.util.Map;

/**
 * ساختارهای داده‌ای دسته‌ها را معرفی می‌کند.
 * 
 * @author maso
 *
 */
public class PCategory {

	Long id;
	Long user;
	Long parent;
	String title;
	String description;
	String color;

	// Date creation_dtime;
	// Date modif_dtime;

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getColor() {
		return color;
	}

	public void setColor(String color) {
		this.color = color;
	}

	public Long getParent() {
		return parent;
	}

	public void setParent(Long parent) {
		this.parent = parent;
	}

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("description", getDescription());
		map.put("color", getColor());

		return map;
	}

}
