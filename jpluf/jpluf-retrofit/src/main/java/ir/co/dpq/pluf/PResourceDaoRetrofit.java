package ir.co.dpq.pluf;

import java.io.File;
import java.net.URL;

import ir.co.dpq.pluf.retrofit.Assert;
import ir.co.dpq.pluf.retrofit.Util;
import ir.co.dpq.pluf.retrofit.saas.IResourceService;
import ir.co.dpq.pluf.retrofit.saas.RResource;
import ir.co.dpq.pluf.saas.IPResourceDao;
import ir.co.dpq.pluf.saas.IPTenantDao;
import ir.co.dpq.pluf.saas.PResource;
import ir.co.dpq.pluf.saas.PTenant;
import retrofit.mime.TypedFile;

public class PResourceDaoRetrofit implements IPResourceDao {

	IResourceService resourceService;

	IPTenantDao tenantDao;

	private TypedFile getFileType(PResource resource) {
		File file = new File(resource.getFilePath(), resource.getFile());
		String mimeType = "application/binary";
		return new TypedFile(mimeType, file);
	}

	@Override
	public PResource create(PResource resource) {
		PTenant tenant = tenantDao.current();
		Assert.assertNotNull(tenantDao, "Current tenant is not set?!");
		RResource ntenant = resourceService.create(tenant.getId(), getFileType(resource), resource.getDescription());
		return ntenant;
	}

	@Override
	public PResource get(Long id) {
		PTenant tenant = tenantDao.current();
		Assert.assertNotNull(tenantDao, "Current tenant is not set?!");
		RResource ntenant = resourceService.get(tenant.getId(), id);
		return ntenant;
	}

	@Override
	public PResource delete(PResource resource) {
		PTenant tenant = tenantDao.current();
		Assert.assertNotNull(tenantDao, "Current tenant is not set?!");
		RResource ntenant = resourceService.delete(tenant.getId(), resource.getId());
		return ntenant;
	}

	@Override
	public PResource update(PResource resource) {
		PTenant tenant = tenantDao.current();
		Assert.assertNotNull(tenantDao, "Current tenant is not set?!");
		RResource rr = Util.toRObject(resource);
		RResource ntenant = resourceService.update(tenant.getId(), resource.getId(), rr.toMap());
		return ntenant;
	}

	@Override
	public URL getFile(PResource resource) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public IPPaginatorPage<PResource> find(PPaginatorParameter param) {
		// TODO Auto-generated method stub
		return null;
	}

	public void setResourceService(IResourceService resourceService) {
		this.resourceService = resourceService;
	}

	public void setTenantDao(IPTenantDao tenantDao) {
		this.tenantDao = tenantDao;
	}
}
