package ir.co.dpq.pluf.retrofit.wiki;

import java.sql.Date;
import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * صفحه‌های راهنمای را ایجاد می‌کند
 * 
 * @author maso
 *
 */
public class RWikiPage extends PWikiPage {

	@SerializedName("content_type")
	private String contentType;

	@SerializedName("creation_dtime")
	Date creation;

	@SerializedName("modif_dtime")
	Date modification;

	public RWikiPage() {
		super();
	}

	public RWikiPage(PWikiPage page) {
		this();

		// copey
		setId(page.getId());
		setState(page.getState());
		setTitle(page.getTitle());
		setLanguage(page.getLanguage());
		setSummary(page.getSummary());

		setPriority(page.getPriority());
		setContent(page.getContent());
		setContentType(page.getContentType());
	}

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("periority", getPriority());
		map.put("state", getState());

		map.put("language", getLanguage());
		map.put("summary", getSummary());
		map.put("content", getContent());
		map.put("content_type", getContentType());

		return map;
	}

	public String getContentType() {
		return contentType;
	}

	public void setContentType(String contentType) {
		this.contentType = contentType;
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
