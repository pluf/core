package ir.co.dpq.pluf.wiki;

import java.sql.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Inheritance;
import javax.persistence.InheritanceType;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

/**
 * صفحه‌های راهنمای را ایجاد می‌کند
 * 
 * @author maso
 *
 */
@Entity(name = "wiki_page")
@Table(name = "wiki_page")
@Inheritance(strategy = InheritanceType.SINGLE_TABLE)
public class PWikiPage extends PWikiPageItem {

	@Column(name = "language")
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
