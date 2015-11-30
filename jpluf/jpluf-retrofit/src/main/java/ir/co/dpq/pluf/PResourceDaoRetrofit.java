package ir.co.dpq.pluf;

import java.io.File;
import java.net.MalformedURLException;
import java.net.URL;

import ir.co.dpq.pluf.retrofit.Assert;
import ir.co.dpq.pluf.retrofit.IRConfigurationService;
import ir.co.dpq.pluf.retrofit.RPaginatorParameter;
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

	IRConfigurationService configurationService;

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
		String path = String.format("%s/api/saas/app/%d/resource/%d/download", //
				configurationService.getEndpoint(), //
				tenantDao.current().getId(), //
				resource.getId());//
		try {
			return new URL(path);
		} catch (MalformedURLException e) {
			throw new PException("", e);
		}
	}

	@Override
	public IPPaginatorPage<PResource> find(PPaginatorParameter param) {
		PTenant tenant = tenantDao.current();
		Assert.assertNotNull(tenantDao, "Current tenant is not set?!");
		RPaginatorParameter rparams = Util.toRObject(param);
		return resourceService.find(tenant.getId(), rparams.toMap());
	}

	public void setResourceService(IResourceService resourceService) {
		this.resourceService = resourceService;
	}

	public void setTenantDao(IPTenantDao tenantDao) {
		this.tenantDao = tenantDao;
	}

	public void setConfigurationService(IRConfigurationService configurationService) {
		this.configurationService = configurationService;
	}
}
