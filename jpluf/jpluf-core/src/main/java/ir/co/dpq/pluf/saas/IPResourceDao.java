package ir.co.dpq.pluf.saas;

import java.net.URL;

import ir.co.dpq.pluf.IPCallback;
import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;

public interface IPResourceDao {

	PResource create(PResource resource);
	void create(PResource resource, IPCallback<PResource> callback);

	PResource get(Long id);
	void get(Long id, IPCallback<PResource> callback);

	PResource delete(PResource resource);
	void delete(PResource resource, IPCallback<PResource> callback);
	
	PResource update(PResource resource);
	void update(PResource resource, IPCallback<PResource> callback);
	
	URL getFile(PResource resource);

	IPPaginatorPage<PResource> find(PPaginatorParameter param);
	void find(PPaginatorParameter param, IPCallback<IPPaginatorPage<PResource>> callback);

}
