package ir.co.dpq.pluf.user;

import com.google.gson.annotations.SerializedName;

/**
 * ساختار داده‌ای کاربر را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class PUser {

	@SerializedName("id")
	long id;

	@SerializedName("login")
	String login;
	
	@SerializedName("first_name")
	String firstName;
	
	@SerializedName("last_name")
	String lastName;
	
	@SerializedName("email")
	String email;
	
	@SerializedName("administrator")
	boolean administrator;
	
	@SerializedName("staff")
	boolean staff;
	
	@SerializedName("active")
	boolean active;
	
	@SerializedName("language")
	String language;
	
	@SerializedName("timezone")
	String timezone;

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public String getLogin() {
		return login;
	}

	public void setLogin(String login) {
		this.login = login;
	}

	public String getFirstName() {
		return firstName;
	}

	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}

	public String getLastName() {
		return lastName;
	}

	public void setLastName(String lastName) {
		this.lastName = lastName;
	}

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
}
