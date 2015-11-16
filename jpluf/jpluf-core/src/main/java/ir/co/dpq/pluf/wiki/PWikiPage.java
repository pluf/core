package ir.co.dpq.pluf.wiki;

import java.sql.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

/**
 * صفحه‌های راهنمای را ایجاد می‌کند
 * 
 * @author maso
 *
 */
@Entity
@Table(name = "pluf_wiki_page")
public class PWikiPage extends PWikiPageItem {

	@Column(name = "state")
	private String language;

	@Column(name = "summary")
	private String summary;

	@Column(name = "content")
	private String content;

	@Column(name = "content_type")
	private String contentType;

	@Column(name = "creation_dtime")
	@Temporal(TemporalType.DATE)
	Date creation;

	@Column(name = "modif_dtime")
	@Temporal(TemporalType.DATE)
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
