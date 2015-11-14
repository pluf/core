package ir.co.dpq.pluf.km;

import java.util.Date;
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

	Date creation;
	Date modification;

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

	public Long getUser() {
		return user;
	}

	public void setUser(Long user) {
		this.user = user;
	}

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

}
