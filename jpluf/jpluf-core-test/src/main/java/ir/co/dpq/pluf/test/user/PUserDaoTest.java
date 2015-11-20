package ir.co.dpq.pluf.test.user;

import static ir.co.dpq.pluf.test.TestCoreConstant.*;
import static org.junit.Assert.*;

import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.user.IPUserDao;
import ir.co.dpq.pluf.user.PUser;

public abstract class PUserDaoTest {

	private IPUserDao usr;

	@Before
	public void createService() {
		usr = getUserDaoInstance();
	}

	protected abstract IPUserDao getUserDaoInstance();

	@Test
	public void getSessionUser() {
		PUser user = usr.getSessionUser();
		assertNotNull(user);
	}

	@Test
	public void login() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals(ADMIN_LOGIN, user.getLogin());
	}

	@Test
	public void login01() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals(ADMIN_LOGIN, user.getLogin());
		assertNotNull(user.getLastLogin());
	}

	@Test(expected = PException.class)
	public void loginFail() {
		PUser user = usr.login("Non user name", "bad password");
		assertNotNull(user);
		assertEquals("admin", user.getLogin());
	}

	@Test
	public void logout() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals("admin", user.getLogin());

		usr.logout();
	}

	@Test
	public void updateUserFirstName() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals("admin", user.getLogin());

		String name = "maostafa" + Math.random();
		user.setFirstName(name);

		PUser nuser = usr.update(user);
		assertNotNull(nuser);
		assertEquals(name, nuser.getFirstName());
	}

	@Test
	public void updateUserEmail() {
		PUser user = usr.login(ADMIN_LOGIN, ADMIN_PASSWORD);
		assertNotNull(user);
		assertEquals(ADMIN_LOGIN, user.getLogin());

		String email = "mostafa.barmshory@dpq.co.ir";
		user.setEmail(email);

		PUser nuser = usr.update(user);
		assertNotNull(nuser);
	}

	@Test
	public void signupUserTest00() {
		usr.logout();
		
		String pass = "pass" + Math.random();

		PUser user = new PUser();
		user.setLogin("login" + Math.random());
		user.setFirstName("first name");
		user.setLastName("last name");
		user.setEmail("mostafa.barmshory@dpq.co.ir");
		user.setPassword(pass);

		PUser nuser = usr.signup(user);
		assertNotNull(nuser);

		PUser u = usr.login(user.getLogin(), pass);
		assertNotNull(u);
		assertEquals(user.getLogin(), u.getLogin());
		
		usr.logout();
	}
}
