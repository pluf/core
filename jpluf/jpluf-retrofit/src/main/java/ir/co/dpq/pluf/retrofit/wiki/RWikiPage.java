package ir.co.dpq.pluf.retrofit.wiki;

import java.sql.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * صفحه‌های راهنمای را ایجاد می‌کند
 * 
 * @author maso
 *
 */
public class RWikiPage extends PWiki {

	private String language;
	private String summary;
	private String content;
	private String contentType;

	Date creation;
	Date modification;

	public String getLanguage() {
		return language;
	}

	public void setLanguage(String language) {
		this.language = language;
	}

	public String getSummary() {
		return summary;
	}

	public void setSummary(String summary) {
		this.summary = summary;
	}

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}

	public String getContentType() {
		return contentType;
	}

	public void setContentType(String contentType) {
		this.contentType = contentType;
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
	
	
}
