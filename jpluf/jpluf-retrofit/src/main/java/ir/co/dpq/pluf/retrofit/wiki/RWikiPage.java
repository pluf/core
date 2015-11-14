package ir.co.dpq.pluf.retrofit.wiki;

import java.util.HashMap;
import java.util.Map;

import ir.co.dpq.pluf.wiki.PWikiPage;

/**
 * صفحه‌های راهنمای را ایجاد می‌کند
 * 
 * @author maso
 *
 */
public class RWikiPage extends PWikiPage {

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("periority", getPriority());
		map.put("state", getState());

		map.put("language", getLanguage());
		map.put("summary", getSummary());
		map.put("content", getContent());
		map.put("content_type", getContentType());

		return map;
	}

}
