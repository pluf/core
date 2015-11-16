package ir.co.dpq.pluf.retrofit.user;

import java.util.Date;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.retrofit.IRObject;
import ir.co.dpq.pluf.user.PProfile;

/**
 * ساختار داده‌ای پروفایل کاربری را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class RProfile extends PProfile implements IRObject {

	@SerializedName("access_count")
	long accessCount;

	@SerializedName("national_id")
	String nationalId;

	@SerializedName("postal_code")
	String postalCode;

	@SerializedName("phone_number")
	String phoneNumber;

	@SerializedName("mobile_number")
	String mobileNumber;

	@SerializedName("creation_dtime")
	Date creation;

	@SerializedName("modif_dtime")
	Date modification;

	public RProfile() {
		super();
	}

	public RProfile(PProfile profile) {
		super();

		// XXX: maso, 1394: رونوشت تمام داده‌ها
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#setPhoneNumber(java.lang.String)
	 */
	public void setPhoneNumber(String phoneNumber) {
		this.phoneNumber = phoneNumber;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#getMobileNumber()
	 */
	public String getMobileNumber() {
		return mobileNumber;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#setMobileNumber(java.lang.String)
	 */
	public void setMobileNumber(String mobileNumber) {
		this.mobileNumber = mobileNumber;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#getAccessCount()
	 */
	public long getAccessCount() {
		return accessCount;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#setAccessCount(long)
	 */
	public void setAccessCount(long accessCount) {
		this.accessCount = accessCount;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#getNationalId()
	 */
	public String getNationalId() {
		return nationalId;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#setNationalId(java.lang.String)
	 */
	public void setNationalId(String nationalId) {
		this.nationalId = nationalId;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#getPostalCode()
	 */
	public String getPostalCode() {
		return postalCode;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#setPostalCode(java.lang.String)
	 */
	public void setPostalCode(String postalCode) {
		this.postalCode = postalCode;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#getCreation()
	 */
	public Date getCreation() {
		return creation;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#setCreation(java.util.Date)
	 */
	public void setCreation(Date creation) {
		this.creation = creation;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#getModification()
	 */
	public Date getModification() {
		return modification;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#setModification(java.util.Date)
	 */
	public void setModification(Date modification) {
		this.modification = modification;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PProfile#getPhoneNumber()
	 */
	public String getPhoneNumber() {
		return phoneNumber;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.retrofit.IRObject#toMap()
	 */
	public Map<String, Object> toMap() {
		// XXX: maso, 1394: تبدیل به یک نگاشت
		return null;
	}

}
