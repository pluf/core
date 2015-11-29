package ir.co.dpq.pluf.file.test;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import java.io.File;
import java.io.IOException;

import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.file.PResourceDaoFile;
import ir.co.dpq.pluf.saas.PResource;
import ir.co.dpq.pluf.test.saas.PResourceDaoTest;

public class PResourceDaoFileBasicTest {

	private PResourceDaoFile resourceDao;

	@Before
	public void getPResourceDao() {
		this.resourceDao = new PResourceDaoFile();
		File dir = new File(PResourceDaoTest.EXAMPLE_FILE_PATH);
		dir.mkdir();
	}

	@Test
	public void storeList() throws IOException {
		File dir = new File("temp2");
		dir.mkdir();

		resourceDao.setBasePath(dir);
		PResource resource = new PResource();
		resource.setFile(PResourceDaoTest.createTestFile().getName());
		resource.setFilePath(PResourceDaoTest.EXAMPLE_FILE_PATH);
		resource.setDescription("This is an example file.");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

		PResourceDaoFile resourceDao2 = new PResourceDaoFile(dir);
		cresource = resourceDao2.get(cresource.getId());
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

	}

}
