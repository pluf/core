package ir.co.dpq.pluf.retrofit.wiki;

import java.util.HashMap;
import java.util.Map;

import ir.co.dpq.pluf.wiki.PWikiBook;

public class RWikiBook extends PWikiBook {

	public Map<String, Object> toMap() {
		HashMap<String, Object> map = new HashMap<String, Object>();

		map.put("id", getId());
		map.put("title", getTitle());
		map.put("language", getLanguage());
		map.put("summary", getSummary());

		return map;
	}
}
