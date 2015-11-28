package ir.co.dpq.pluf.test.saas;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;

import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.saas.IPResourceDao;
import ir.co.dpq.pluf.saas.PResource;

public abstract class PResourceDaoTest {
	private static final String EXAMPLE_FILE = "example.txt";
	private static final String EXAMPLE_FILE_PATH = ".";

	private IPResourceDao resourceDao;

	@Before
	public void createService() {
		resourceDao = getPResourceDao();
	}

	@Before
	public void createTestFile() throws IOException {
		File file = new File(EXAMPLE_FILE_PATH, EXAMPLE_FILE);
		FileOutputStream out = new FileOutputStream(file);
		out.write("Example file".getBytes());
		out.flush();
		out.close();
	}

	protected abstract IPResourceDao getPResourceDao();

	@Test
	public void createResourceTest00() {
		PResource resource = new PResource();
		resource.setFile(EXAMPLE_FILE);
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("This is an example file.");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
	}

	@Test
	public void getResourceTest00() {
		PResource resource = new PResource();
		resource.setFile(EXAMPLE_FILE);
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("This is an example file.");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
		
		cresource = resourceDao.get(cresource.getId());
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
	}
}
