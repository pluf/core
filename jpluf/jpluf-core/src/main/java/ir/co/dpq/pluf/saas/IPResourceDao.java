package ir.co.dpq.pluf.saas;

import java.io.File;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PPaginatorParameter;

public interface IPResourceDao {

	PResource create(PResource resource);

	PResource get(Long id);

	PResource delete(PResource resource);
	
	PResource update(PResource resource);
	
	File getFile(PResource resource);

	IPPaginatorPage<PResource> find(PPaginatorParameter param);

}
