package ir.co.dpq.pluf.file.test;

import ir.co.dpq.pluf.file.PResourceDaoFile;
import ir.co.dpq.pluf.saas.IPResourceDao;
import ir.co.dpq.pluf.test.saas.PResourceDaoTest;

public class PResourceDaoFileTest extends PResourceDaoTest {

	private PResourceDaoFile resourceDao;

	public PResourceDaoFileTest() {
		this.resourceDao = new PResourceDaoFile();
	}
	
	@Override
	protected IPResourceDao getPResourceDao() {
		return resourceDao;
	}

}
