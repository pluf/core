package ir.co.dpq.pluf;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;
import java.util.Map.Entry;
import java.util.Set;

import com.google.gson.*;
import com.google.gson.reflect.TypeToken;

/**
 * ساختارهای داده‌ای صفحه بندی شده را دیکد می‌کند.
 * 
 * @author maso
 *
 * @param <T>
 */
public class DeserializerJson<T> implements JsonDeserializer<PPaginatorPage<T>> {

    @Override
    public PPaginatorPage<T> deserialize(JsonElement je, Type type, JsonDeserializationContext jdc)
            throws JsonParseException {
        Gson gson = new Gson();
        Type paginatorPageType = new TypeToken<PPaginatorPage<T>>() {}.getType();
        PPaginatorPage<T> pagedResponse = gson.fromJson(je, paginatorPageType);

        Type ct = new TypeToken<T>() {}.getType();
        JsonElement jeI = je.getAsJsonObject().get("items");
        List<T> items = new ArrayList<T>();
        Set<Entry<String, JsonElement>> set = jeI.getAsJsonObject().entrySet();
        for (Entry<String, JsonElement> entry : set) {
			T v = gson.fromJson(entry.getValue(), ct);
			items.add(v);
		}
        pagedResponse.setItems(items);
        return pagedResponse;
    }
}