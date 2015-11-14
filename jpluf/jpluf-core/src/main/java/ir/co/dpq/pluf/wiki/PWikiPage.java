package ir.co.dpq.pluf.wiki;

import java.sql.Date;

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

}
