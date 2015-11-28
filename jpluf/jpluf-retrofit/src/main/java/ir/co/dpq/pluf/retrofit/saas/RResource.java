package ir.co.dpq.pluf.retrofit.saas;

import java.util.Date;
import java.util.Map;

import com.google.gson.annotations.SerializedName;

import ir.co.dpq.pluf.retrofit.IRObject;
import ir.co.dpq.pluf.saas.PResource;

public class RResource extends PResource implements IRObject {

	@SerializedName("file_path")
	String filePath;

	@SerializedName("file_size")
	Long fileSize;

	@SerializedName("mime_type")
	String mimeType;

	@SerializedName("creation_dtime")
	Date creation;

	@SerializedName("modif_dtime")
	Date modification;

	public String getFilePath() {
		return filePath;
	}

	public void setFilePath(String filePath) {
		this.filePath = filePath;
	}

	public Long getFileSize() {
		return fileSize;
	}

	public void setFileSize(Long fileSize) {
		this.fileSize = fileSize;
	}

	public String getMimeType() {
		return mimeType;
	}

	public void setMimeType(String mimeType) {
		this.mimeType = mimeType;
	}

	public Date getCreation() {
		return creation;
	}

	public void setCreation(Date creation) {
		this.creation = creation;
	}

	public Date getModification() {
		return modification;
	}

	public void setModification(Date modification) {
		this.modification = modification;
	}

	@Override
	public Map<String, Object> toMap() {
		// TODO Auto-generated method stub
		return null;
	}
}
