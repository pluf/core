package ir.co.dpq.pluf.file;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;

import ir.co.dpq.pluf.saas.PResource;

public class FileUtil {

	public static void copyFile(PResource src, PResource dis) throws IOException {
		File srcFile = toJavaFile(src);
		File disFile = toJavaFile(dis);
		FileInputStream input = new FileInputStream(srcFile);
		FileOutputStream output = new FileOutputStream(disFile);

		byte[] buffer = new byte[1024];
		int l = input.read(buffer);
		while (l > 0) {
			output.write(buffer, 0, l);
			l = input.read(buffer);
		}

		input.close();
		output.close();
	}

	public static File toJavaFile(PResource src) {
		return new File(src.getFilePath(), src.getFile());
	}

}
