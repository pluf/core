package ir.co.dpq.pluf.test.saas;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.net.URL;

import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.IPPaginatorPage;
import ir.co.dpq.pluf.PException;
import ir.co.dpq.pluf.PPaginatorParameter;
import ir.co.dpq.pluf.saas.IPResourceDao;
import ir.co.dpq.pluf.saas.PResource;

public abstract class PResourceDaoTest {
	public static final String EXAMPLE_FILE = "example_prefix_";
	public static final String EXAMPLE_FILE_PATH = "example";
	public static final String EXAMPLE_POSTFIX = ".txt";

	private IPResourceDao resourceDao;

	@Before
	public void createService() {
		resourceDao = getPResourceDao();
	}

	@Before
	public void createTestPath() {
		File dir = new File(EXAMPLE_FILE_PATH);
		dir.mkdir();
	}

	public static File createTestFile() throws IOException {
		File file = new File(EXAMPLE_FILE_PATH, EXAMPLE_FILE + Math.random() + EXAMPLE_POSTFIX);
		FileOutputStream out = new FileOutputStream(file);
		out.write("Example file".getBytes());
		out.flush();
		out.close();
		return file;
	}

	protected abstract IPResourceDao getPResourceDao();

	@Test
	public void createResourceTest00() throws IOException {
		PResource resource = new PResource();
		resource.setFile(createTestFile().getName());
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("createResourceTest00");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
	}

	@Test
	public void getResourceTest00() throws IOException {
		PResource resource = new PResource();
		resource.setFile(createTestFile().getName());
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("getResourceTest00");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

		cresource = resourceDao.get(cresource.getId());
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
	}

	@Test
	public void deleteResourceTest00() throws IOException {
		PResource resource = new PResource();
		resource.setFile(createTestFile().getName());
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("getResourceTest00");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

		cresource = resourceDao.delete(cresource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
	}

	@Test(expected = PException.class)
	public void deleteResourceTest01() throws IOException {
		PResource resource = new PResource();
		resource.setFile(createTestFile().getName());
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("getResourceTest00");

		PResource cresource = resourceDao.create(resource);
		long cid = cresource.getId();
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

		cresource = resourceDao.delete(cresource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

		resourceDao.get(cid);
	}

	@Test
	public void updateResourceTest00() throws IOException {
		PResource resource = new PResource();
		resource.setFile(createTestFile().getName());
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("getResourceTest00");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

		String str = "Description :" + Math.random();
		cresource.setDescription(str);

		cresource = resourceDao.update(cresource);
		assertNotNull(cresource);
		assertEquals(str, cresource.getDescription());
	}

	@Test
	public void getFileResourceTest00() throws IOException {
		PResource resource = new PResource();
		resource.setFile(createTestFile().getName());
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("getResourceTest00");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());

		URL file = resourceDao.getFile(cresource);
		assertNotNull(file);
	}

	@Test
	public void findResourceTest00() {
		PPaginatorParameter param = new PPaginatorParameter();
		IPPaginatorPage<PResource> list = resourceDao.find(param);
		assertNotNull(list);
		assertNotNull(list.getItems());
	}
}
