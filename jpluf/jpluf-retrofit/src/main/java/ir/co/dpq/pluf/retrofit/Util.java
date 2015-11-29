package ir.co.dpq.pluf.retrofit;

import java.io.File;
import java.util.HashMap;

import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.retrofit.saas.RResource;
import ir.co.dpq.pluf.retrofit.user.RProfile;
import ir.co.dpq.pluf.retrofit.user.RUser;
import ir.co.dpq.pluf.retrofit.wiki.RWikiBook;
import ir.co.dpq.pluf.retrofit.wiki.RWikiPage;
import ir.co.dpq.pluf.saas.PResource;
import ir.co.dpq.pluf.user.PProfile;
import ir.co.dpq.pluf.user.PUser;
import ir.co.dpq.pluf.wiki.PWikiBook;
import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * برخی از فراخوانی‌های پرکاربرد سیستم را ایجاد می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
public class Util {

	public static RProfile toRObject(PProfile profile) {
		if (profile instanceof RProfile)
			return (RProfile) profile;
		return new RProfile(profile);
	}

	public static RWikiBook toRObject(PWikiBook book) {
		if (book instanceof RWikiBook)
			return (RWikiBook) book;
		return new RWikiBook(book);
	}

	public static RPaginatorParameter toRObject(PPaginatorParameter param) {
		if (param instanceof RPaginatorParameter)
			return (RPaginatorParameter) param;
		return new RPaginatorParameter(param);
	}

	public static RUser toRObject(PUser user) {
		if (user instanceof RUser)
			return (RUser) user;
		return new RUser(user);
	}

	public static RWikiPage toRObject(PWikiPage page) {
		if (page instanceof RWikiPage)
			return (RWikiPage) page;
		return new RWikiPage(page);
	}

	public static void filterMap(HashMap<String, Object> map) {
		Object[] keys = map.keySet().toArray();
		for (Object key : keys) {
			if (map.get(key) == null) {
				map.remove(key);
			}
		}
	}

	public static File localFile(PResource resource) {
		return new File(resource.getFilePath(), resource.getFile());
	}

	public static RResource toRObject(PResource resource) {
		if (resource instanceof RResource)
			return (RResource) resource;
		return new RResource(resource);
	}
}
