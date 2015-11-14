package ir.co.dpq.pluf.km;

import java.util.Date;

/**
 * ساختار داده‌ای یک برچسب را تعیین می‌کند.
 * 
 * @author maso
 *
 */
public class PLabel {

	Long id;
	Long user;
	Boolean community;
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

	public Boolean getCommunity() {
		return community;
	}

	public void setCommunity(Boolean community) {
		this.community = community;
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

}
