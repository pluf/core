package ir.co.dpq.pluf.retrofit.user;

import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.retrofit.IRObject;
import ir.co.dpq.pluf.user.PUser;

/**
 * ساختار داده‌ای کاربر را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class RUser extends PUser implements IRObject {

	@SerializedName("first_name")
	private String firstName;

	@SerializedName("last_name")
	private String lastName;

	@SerializedName("date_joined")
	Date dateJoined;

	@SerializedName("last_login")
	Date lastLogin;

	public RUser() {
		super();
	}

	public RUser(PUser user) {
		super();

		// copy attrigutes
		setId(user.getId());
		setEmail(user.getEmail());
		setAdministrator(user.isAdministrator());
		setStaff(user.isStaff());
		setActive(user.isActive());
		setLanguage(user.getLanguage());
		setTimezone(user.getTimezone());
		setDateJoined(user.getDateJoined());
		setLastLogin(user.getLastLogin());
	}

	public String getFirstName() {
		return firstName;
	}

	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}

	public Date getDateJoined() {
		return dateJoined;
	}

	public void setDateJoined(Date dateJoined) {
		this.dateJoined = dateJoined;
	}

	public Date getLastLogin() {
		return lastLogin;
	}

	public void setLastLogin(Date lastLogin) {
		this.lastLogin = lastLogin;
	}

	public String getLastName() {
		return lastName;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.user.PUserItem#setLastName(java.lang.String)
	 */
	public void setLastName(String lastName) {
		this.lastName = lastName;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see ir.co.dpq.pluf.retrofit.IRObject#toMap()
	 */
	@Override
	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();
		
		map.put("email", getEmail());
		map.put("active", isActive());
		map.put("administrator", isAdministrator());
		map.put("staff", isStaff());
		map.put("language", getLanguage());
		map.put("timezone", getTimezone());
		map.put("first_name", getFirstName());
		map.put("last_name", getLastName());
		
		return map;
	}
}
