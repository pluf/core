package ir.co.dpq.pluf.wiki;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

@Entity
@Table(name = "pluf_wiki_book")
public class PWikiBook {

	@Id
	@Column(name = "book_id", nullable = false)
	Long id;

	@Column(name = "state")
	Integer state;

	@Column(name = "title", nullable = true)
	String title;

	@Column(name = "language")
	String language;

	@Column(name = "summary", nullable = true)
	String summary;

	@Column(name = "creation_dtime")
	@Temporal(TemporalType.DATE)
	Date creation;

	@Column(name = "modif_dtime")
	@Temporal(TemporalType.DATE)
	Date modification;

	public PWikiBook() {
		// TODO Auto-generated constructor stub
	}

	public PWikiBook(PWikiBook book) {
		update(book);
	}

	public void update(PWikiBook book) {
		this.state = book.state;
		this.title = book.title;
		this.language = book.language;
		this.summary = book.summary;
	}

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public Integer getState() {
		return state;
	}

	public void setState(Integer state) {
		this.state = state;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

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
