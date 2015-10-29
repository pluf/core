package ir.co.dpq.pluf.wiki;

import java.util.HashMap;
import java.util.Map;

public class PWikiBook {

	Long id;
	Integer state;

	String title;
	String language;
	String summary;

	// @SerializedName("creation_dtime")
	// Date creationTime;

	// @SerializedName("modif_dtime")
	// Date modifTime;

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

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("language", getLanguage());
		map.put("summary", getSummary());

		return map;
	}
}
