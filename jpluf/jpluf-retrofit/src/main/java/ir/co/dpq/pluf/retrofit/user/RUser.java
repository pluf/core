package ir.co.dpq.pluf.retrofit.user;

import java.util.Date;

import com.google.gson.annotations.SerializedName;

/**
 * ساختار داده‌ای کاربر را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class RUser extends RUserItem {

	@SerializedName("email")
	private String email;

	@SerializedName("administrator")
	private boolean administrator;

	@SerializedName("staff")
	private boolean staff;

	@SerializedName("active")
	private boolean active;

	@SerializedName("language")
	private String language;

	@SerializedName("timezone")
	private String timezone;

	@SerializedName("date_joined")
	Date dateJoined;

	@SerializedName("last_login")
	Date lastLogin;

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public boolean isAdministrator() {
		return administrator;
	}

	public void setAdministrator(boolean administrator) {
		this.administrator = administrator;
	}

	public boolean isStaff() {
		return staff;
	}

	public void setStaff(boolean staff) {
		this.staff = staff;
	}

	public boolean isActive() {
		return active;
	}

	public void setActive(boolean active) {
		this.active = active;
	}

	public String getLanguage() {
		return language;
	}

	public void setLanguage(String language) {
		this.language = language;
	}

	public String getTimezone() {
		return timezone;
	}

	public void setTimezone(String timezone) {
		this.timezone = timezone;
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
	
	
}
