package ir.co.dpq.pluf.retrofit.jayab;

import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

/**
 * Created by hadi on 6/11/15.
 */
public class Location {

	// Key attributes of location
	long id;
	boolean community;
	String name;
	String description;
	Long reporter;
	Double latitude;
	Double longitude;
	// TODO Hadi 1394-07-07: دو فیلد زیر به نوع DateTime جاوا تبدیل شوند
	@SerializedName("creation_dtime")
	String creationDateTime;
	@SerializedName("modif_dtime")
	String modificationDateTime;

	// For cloud base system
	/**
	 * مالک مکان که می‌تواند یک کاربر یا یک گروه یا یک tenant باشد.
	 */
	@SerializedName("owner_id")
	long ownerId;
	/**
	 * یکی از مقادیر user, group, tenant
	 */
	@SerializedName("owner_class")
	String ownerClass;

	/**
	 * یک نمونه جدید از این کلاس ایجاد می‌کند.
	 */
	public Location(){
		// 
	}
	
	public Location(Long id, Double lat, Double lon) {
		setId(id);
		setLatitude(lat);
		setLongitude(lon);
		setName(name);
	}

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public Double getLatitude() {
		return latitude;
	}

	public void setLatitude(Double latitude) {
		this.latitude = latitude;
	}

	public Double getLongitude() {
		return longitude;
	}

	public void setLongitude(Double longitude) {
		this.longitude = longitude;
	}

	public Long getReporter() {
		return reporter;
	}

	public void setReporter(Long reporter) {
		this.reporter = reporter;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public boolean isCommunity() {
		return community;
	}

	public void setCommunity(boolean community) {
		this.community = community;
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

	public long getOwnerId() {
		return ownerId;
	}

	public void setOwnerId(long ownerId) {
		this.ownerId = ownerId;
	}

	public String getOwnerClass() {
		return ownerClass;
	}

	public void setOwnerClass(String ownerClass) {
		this.ownerClass = ownerClass;
	}

	/**
	 * داده‌های کلاس را به صورت یک نگاشت می‌دهد.
	 * 
	 * @return
	 */
	public Map<String, Object> map(){
		HashMap<String, Object> m = new HashMap<String, Object>();
		if(getLatitude() != null){
			m.put("latitude", getLatitude());
		}
		if(getLongitude() != null){
			m.put("longitude", getLongitude());
		}
		if(getName() != null){
			m.put("name", getName());
		}
		if(getDescription() != null){
			m.put("description", getDescription());
		}
		// FIXME: maso, 1394: add other information.
		return m;
	}
}
