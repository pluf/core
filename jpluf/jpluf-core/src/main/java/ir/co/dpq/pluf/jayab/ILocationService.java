package ir.co.dpq.pluf.jayab;

import ir.co.dpq.pluf.IPCallback;
import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;

public interface ILocationService {

	void findLocation(double latitude, double longitude, Integer count, Double radius, String tagKey, String tagValue,
			IPCallback<IPPaginatorPage<PLocation>> callBack);

	IPPaginatorPage<PLocation> findLocation(double latitude, double longitude, Integer count, Double radius,
			String tagKey, String tagValue);

	void listLocations(PPaginatorParameter params, IPCallback<IPPaginatorPage<PLocation>> callBack);

	IPPaginatorPage<PLocation> listLocation(PPaginatorParameter params);

	PLocation createLocation(PLocation location);

	/**
	 * یک نمونه جدید از مکان را ایجاد می‌کند
	 * 
	 * پارامترهای این فراخوانی به صورت یک نگاشت تعیین می‌شود.
	 * 
	 * @see Location#map()
	 * @param params
	 * @param callBack
	 */
	void createLocation(PLocation location, IPCallback<PLocation> callBack);

	PLocation updateLocation(PLocation location);

	void updateLocation(PLocation location, IPCallback<PLocation> callBack);

	PLocation getLocation(Long placeId);

	void getLocation(Long placeId, IPCallback<PLocation> callBack);

	PLocation deleteLocation(PLocation location);

	void deleteLocation(PLocation location, IPCallback<PLocation> callBack);

	// *************************************************************************
	// Tag Management
	// *************************************************************************

	void addTag(PLocation location, PTag tag, IPCallback<PLocation> callBack);

	PLocation addTag(PLocation location, PTag tag);

	void deleteTag(PLocation location, PTag tag, IPCallback<PLocation> callBack);

	PLocation deleteTag(PLocation location, PTag tag);

	// *************************************************************************
	// Vote Management
	// *************************************************************************

	void getVoteSummary(PLocation placeId, IPCallback<PVoteSummary> callBack);

	PVoteSummary getVoteSummary(PLocation placeId);

	void getVote(PLocation placeId, IPCallback<PVote> callBack);

	PVote getVote(PLocation placeId);

	void deleteVote(PLocation placeId, IPCallback<PVote> callBack);

	PVote deleteVote(PLocation placeId);

	void setVote(PLocation placeId, Boolean vote, IPCallback<PVote> callBack);

	PVote setVote(PLocation placeId, Boolean vote);

}
