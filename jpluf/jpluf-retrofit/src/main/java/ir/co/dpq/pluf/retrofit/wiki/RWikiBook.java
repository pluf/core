package ir.co.dpq.pluf.retrofit.wiki;

import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.retrofit.IRObject;
import ir.co.dpq.pluf.wiki.PWikiBook;

public class RWikiBook extends PWikiBook implements IRObject {

	@SerializedName("creation_dtime")
	Date creation;

	@SerializedName("modif_dtime")
	Date modification;

	/**
	 * یک نمونه جدید از این کلاس ایجاد می‌کند
	 * 
	 */
	public RWikiBook() {
		super();
	}

	/**
	 * یک نمونه جدید از این کلاس ایجاد می‌کند.
	 * 
	 * @param book
	 */
	public RWikiBook(PWikiBook book) {
		setId(book.getId());
		setState(book.getState());
		setTitle(book.getTitle());
		setLanguage(book.getLanguage());
		setSummary(book.getSummary());
		
		setCreation(book.getCreation());
		setModification(book.getModification());
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.retrofit.IRObject#toMap()
	 */
	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("language", getLanguage());
		map.put("summary", getSummary());

		return map;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.wiki.PWikiBook#getCreation()
	 */
	public Date getCreation() {
		return creation;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.wiki.PWikiBook#setCreation(java.util.Date)
	 */
	public void setCreation(Date creation) {
		this.creation = creation;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.wiki.PWikiBook#getModification()
	 */
	public Date getModification() {
		return modification;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.wiki.PWikiBook#setModification(java.util.Date)
	 */
	public void setModification(Date modification) {
		this.modification = modification;
	}
}
