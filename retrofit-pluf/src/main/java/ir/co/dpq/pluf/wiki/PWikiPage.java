package ir.co.dpq.pluf.wiki;

import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

/**
 * صفحه‌های راهنمای را ایجاد می‌کند
 * 
 * @author maso
 *
 */
public class PWikiPage extends PWikiPageItem {

	private String language;
	private String summary;
	private String content;

	@SerializedName("content_type")
	private String contentType;

	// creation_dtime
	// modif_dtime : Datetime

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
