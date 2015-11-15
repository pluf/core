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

	public void setPhoneNumber(String phoneNumber) {
		this.phoneNumber = phoneNumber;
	}

	public String getMobileNumber() {
		return mobileNumber;
	}

	public void setMobileNumber(String mobileNumber) {
		this.mobileNumber = mobileNumber;
	}

	public long getAccessCount() {
		return accessCount;
	}

	public void setAccessCount(long accessCount) {
		this.accessCount = accessCount;
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

	public String getPhoneNumber() {
		return phoneNumber;
	}

	public Map<String, Object> toMap() {
		// TODO Auto-generated method stub
		return null;
	}

}
