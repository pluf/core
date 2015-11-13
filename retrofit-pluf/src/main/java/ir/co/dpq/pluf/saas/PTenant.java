package ir.co.dpq.pluf.saas;

import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class PTenant {

	Long id;
	Long level;

	@SerializedName("access_count")
	Long accessCount;
	Boolean validate;
	String title;
	String domain;
	String description;
	String logo;
	String background;
	Long sap;

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public Long getLevel() {
		return level;
	}

	public void setLevel(Long level) {
		this.level = level;
	}

	public Long getAccessCount() {
		return accessCount;
	}

	public void setAccessCount(Long accessCount) {
		this.accessCount = accessCount;
	}

	public Boolean getValidate() {
		return validate;
	}

	public void setValidate(Boolean validate) {
		this.validate = validate;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getDomain() {
		return domain;
	}

	public void setDomain(String domain) {
		this.domain = domain;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getLogo() {
		return logo;
	}

	public void setLogo(String logo) {
		this.logo = logo;
	}

	public String getBackground() {
		return background;
	}

	public void setBackground(String background) {
		this.background = background;
	}

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("description", getDescription());

		return map;
	}

	public Long getSap() {
		return sap;
	}

	public void setSap(Long sap) {
		this.sap = sap;
	}

}
