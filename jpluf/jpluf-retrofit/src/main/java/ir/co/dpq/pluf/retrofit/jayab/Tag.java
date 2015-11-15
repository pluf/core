package ir.co.dpq.pluf.retrofit.jayab;

import com.google.gson.annotations.SerializedName;

/**
 * Created by hadi on 9/24/15.
 */
public class Tag {

	public static class Key {
		public static final String AMENITY = "amenity";
	}

	public static class Value {
		public static final String PARKING = "parking";
		public static final String FUEL = "fuel";
		public static final String TOILETS = "toilets";
	}

	long id;
	@SerializedName("tag_key")
	String tagKey;
	@SerializedName("tag_value")
	String tagValue;
	String description;
	@SerializedName("creation_dtime")
	String creationDateTime;
	@SerializedName("modif_dtime")
	String modificationDateTime;

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public String getTagKey() {
		return tagKey;
	}

	public void setTagKey(String tagKey) {
		this.tagKey = tagKey;
	}

	public String getTagValue() {
		return tagValue;
	}

	public void setTagValue(String tagValue) {
		this.tagValue = tagValue;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getCreationDateTime() {
		return creationDateTime;
	}

	public void setCreationDateTime(String creationDateTime) {
		this.creationDateTime = creationDateTime;
	}

	public String getModificationDateTime() {
		return modificationDateTime;
	}

	public void setModificationDateTime(String modificationDateTime) {
		this.modificationDateTime = modificationDateTime;
	}

}
