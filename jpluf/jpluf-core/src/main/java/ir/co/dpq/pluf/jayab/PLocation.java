package ir.co.dpq.pluf.jayab;

import java.util.Date;

/**
 * Created by hadi on 6/11/15.
 */
public class PLocation {

	// Key attributes of location
	long id;
	boolean community;
	String name;
	String description;
	Long reporter;
	Double latitude;
	Double longitude;
	Date creation;
	Date modification;

	/**
	 * مالک مکان که می‌تواند یک کاربر یا یک گروه یا یک tenant باشد.
	 */
	long ownerId;
	/**
	 * یکی از مقادیر user, group, tenant
	 */
	String ownerClass;

	/**
	 * یک نمونه جدید از این کلاس ایجاد می‌کند.
	 */
	public PLocation() {
		//
	}

	public PLocation(Long id, Double lat, Double lon) {
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

}
