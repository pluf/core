package ir.co.dpq.pluf.retrofit.user;

import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.retrofit.IRObject;
import ir.co.dpq.pluf.user.PUserItem;

/**
 * خلاصه اطلاعات یک فرد.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class RUserItem extends PUserItem implements IRObject{


	@SerializedName("first_name")
	private String firstName;

	@SerializedName("last_name")
	private String lastName;

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

	@Override
	public Map<String, Object> toMap() {
		// TODO Auto-generated method stub
		return null;
	}

}
