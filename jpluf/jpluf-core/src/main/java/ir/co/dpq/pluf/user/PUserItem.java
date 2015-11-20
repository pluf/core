package ir.co.dpq.pluf.user;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Inheritance;
import javax.persistence.InheritanceType;
import javax.persistence.Table;

/**
 * خلاصه اطلاعات یک فرد.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
@Entity(name = "user-item")
@Table(name = "user-item")
@Inheritance(strategy = InheritanceType.SINGLE_TABLE)
public class PUserItem {

	@Id
	@Column(name = "_id")
	private long id;

	@Column(name = "login")
	private String login;

	@Column(name = "first_name")
	private String firstName;

	@Column(name = "last_name")
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
