package ir.co.dpq.pluf.jayab;

/**
 * Created by hadi on 9/24/15.
 */
public class PTag {

	Long id;
	String tagKey;
	String tagValue;
	String description;
	String creation;
	String modification;

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

}
