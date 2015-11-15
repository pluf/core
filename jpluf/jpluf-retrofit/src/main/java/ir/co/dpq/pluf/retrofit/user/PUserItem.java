package ir.co.dpq.pluf.retrofit.user;

import com.google.gson.annotations.SerializedName;

/**
 * خلاصه اطلاعات یک فرد.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class PUserItem {

	@SerializedName("id")
	private long id;

	@SerializedName("login")
	private String login;

	@SerializedName("first_name")
	private String firstName;

	@SerializedName("last_name")
	private String lastName;

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

}
