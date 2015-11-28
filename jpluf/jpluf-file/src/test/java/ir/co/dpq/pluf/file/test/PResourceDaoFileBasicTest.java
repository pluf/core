package ir.co.dpq.pluf.file.test;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;

import org.junit.Before;
import org.junit.Test;

import ir.co.dpq.pluf.file.PResourceDaoFile;
import ir.co.dpq.pluf.saas.PResource;

public class PResourceDaoFileBasicTest {
	private static final String EXAMPLE_FILE = "example2.txt";
	private static final String EXAMPLE_FILE_PATH = ".";

	private PResourceDaoFile resourceDao;

	@Before
	public void getPResourceDao() {
		this.resourceDao = new PResourceDaoFile();
	}

	@Before
	public void createTestFile() throws IOException {
		File file = new File(EXAMPLE_FILE_PATH, EXAMPLE_FILE);
		FileOutputStream out = new FileOutputStream(file);
		out.write("Example file".getBytes());
		out.flush();
		out.close();
	}

	@Test
	public void storeList(){
		File dir = new File("temp2");
		dir.mkdir();
		
		resourceDao.setBasePath(dir);
		PResource resource = new PResource();
		resource.setFile(EXAMPLE_FILE);
		resource.setFilePath(EXAMPLE_FILE_PATH);
		resource.setDescription("This is an example file.");

		PResource cresource = resourceDao.create(resource);
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
		
		PResourceDaoFile resourceDao2 = new PResourceDaoFile();
		resourceDao2.setBasePath(dir);
		
		cresource = resourceDao2.get(cresource.getId());
		assertNotNull(cresource);
		assertEquals(resource.getFile(), cresource.getFile());
		assertEquals(resource.getDescription(), cresource.getDescription());
		
	}

}
