package ir.co.dpq.pluf.wiki;

import java.util.HashMap;
import java.util.Map;

public class PWikiBook {

	long id;
	int state;

	String title;
	String language;
	String summary;

	// @SerializedName("creation_dtime")
	// Date creationTime;

	// @SerializedName("modif_dtime")
	// Date modifTime;

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public int getState() {
		return state;
	}

	public void setState(int state) {
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

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("language", getLanguage());
		map.put("summary", getSummary());

		return map;
	}
}
