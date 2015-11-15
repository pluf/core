package ir.co.dpq.pluf.user;

import java.util.Date;

/**
 * ساختار داده‌ای پروفایل کاربری را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class PProfile {

	long id;

	long user;

	/**
	 * سطح یک کار بر را تعیین می‌کند.
	 * 
	 * @see #getLevel()
	 */
	long level;

	long accessCount;

	boolean validate;

	String title;

	String state;

	String city;

	String country;

	String address;

	String nationalId;

	String postalCode;

	String phoneNumber;

	String mobileNumber;

	String shaba;

	Date creation;

	Date modification;

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public long getUser() {
		return user;
	}

	public void setUser(long user) {
		this.user = user;
	}

	/**
	 * سطح کاربر را تعیین می‌کند.
	 * 
	 * کاربرها بر اساس فعالیت‌ها و یا پرداخت‌هایی که در سیستم دارند، یک سطح خاص
	 * برای آنها تعیین می‌شود. سطح کاربر در دسترسی آنها و نحوه استفاده آنها از
	 * سیستم تاثیر گذار است.
	 * 
	 * میزان تاثیر سطح کاربر در دسترسی آن بر اساس نوع سیاست‌های نرم افزارها
	 * تعیین می‌شود.
	 * 
	 * @return
	 */
	public long getLevel() {
		return level;
	}

	/**
	 * سطح کاربر را تعیین می‌کند.
	 * 
	 * @see #getLevel()
	 * @param level
	 */
	public void setLevel(long level) {
		this.level = level;
	}

	public long getAccessCount() {
		return accessCount;
	}

	public void setAccessCount(long accessCount) {
		this.accessCount = accessCount;
	}

	public boolean isValidate() {
		return validate;
	}

	public void setValidate(boolean validate) {
		this.validate = validate;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getState() {
		return state;
	}

	public void setState(String state) {
		this.state = state;
	}

	public String getCity() {
		return city;
	}

	public void setCity(String city) {
		this.city = city;
	}

	public String getCountry() {
		return country;
	}

	public void setCountry(String country) {
		this.country = country;
	}

	public String getAddress() {
		return address;
	}

	public void setAddress(String address) {
		this.address = address;
	}

	public String getNationalId() {
		return nationalId;
	}

	public void setNationalId(String nationalId) {
		this.nationalId = nationalId;
	}

	public String getPostalCode() {
		return postalCode;
	}

	public void setPostalCode(String postalCode) {
		this.postalCode = postalCode;
	}

	public String getPhoneNumber() {
		return phoneNumber;
	}

	public void setPhoneNumber(String phoneNumber) {
		this.phoneNumber = phoneNumber;
	}

	public String getMobileNumber() {
		return mobileNumber;
	}

	public void setMobileNumber(String mobileNumber) {
		this.mobileNumber = mobileNumber;
	}

	public String getShaba() {
		return shaba;
	}

	public void setShaba(String shaba) {
		this.shaba = shaba;
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
