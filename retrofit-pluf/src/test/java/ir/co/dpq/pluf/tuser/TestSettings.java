package ir.co.dpq.pluf.tuser;

import ir.co.dpq.pluf.user.PUser;

public class TestSettings {

	public PUser user;
	public String password;
	public PUser admin;
	public String adminPassword;	
	public String apiUrl;
	
	
	public TestSettings(){
		user = new PUser();
		user.setLogin("test");
		user.setFirstName("test");
		user.setLastName("hastim");
		user.setEmail("");
		user.setLanguage("fa");
		user.setTimezone("Europe/Berlin");
		
		password = "123456";
		
		
		admin = new PUser();
		admin.setLogin("admin");
		
		
		adminPassword = "admin";
		
		
		apiUrl = "http://jahanjoo.ir";
	}
}
