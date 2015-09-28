package ir.co.dpq.pluf.wiki;

/**
 * صفحه‌های راهنمای را ایجاد می‌کند
 * 
 * @author maso
 *
 */
public class PWikiPage {

	long id;
	String title;
	String language;
	String summary;
	String content;

	// creation_dtime
	// modif_dtime : Datetime
	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
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

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}
}
