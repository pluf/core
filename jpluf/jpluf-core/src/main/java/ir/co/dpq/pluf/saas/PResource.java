package ir.co.dpq.pluf.saas;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Inheritance;
import javax.persistence.InheritanceType;
import javax.persistence.Table;

@Entity(name = "saas_resource")
@Table(name = "saas_resource")
@Inheritance(strategy = InheritanceType.SINGLE_TABLE)
public class PResource {

	@Column(name = "resource_id")
	Long id;

	@Column(name = "file")
	String file;

	@Column(name = "file_path")
	String filePath;

	@Column(name = "file_size")
	Long fileSize;

	@Column(name = "mime_type")
	String mimeType;

	@Column(name = "downloads")
	Long downloads;

	@Column(name = "description")
	String description;

	@Column(name = "creation_dtime")
	Date creation;

	@Column(name = "modif_dtime")
	Date modification;

	@Column(name = "application")
	Long application;

	@Column(name = "submitter")
	Long submitter;

	public PResource() {
		// TODO Auto-generated constructor stub
	}
	
	public PResource(PResource resource) {
		setFile(resource.getFile());
		setFilePath(resource.getFilePath());
		setDescription(resource.getDescription());
	}

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public String getFile() {
		return file;
	}

	public void setFile(String file) {
		this.file = file;
	}

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

	public Long getDownloads() {
		return downloads;
	}

	public void setDownloads(Long downloads) {
		this.downloads = downloads;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
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

	public Long getApplication() {
		return application;
	}

	public void setApplication(Long application) {
		this.application = application;
	}

	public Long getSubmitter() {
		return submitter;
	}

	public void setSubmitter(Long submitter) {
		this.submitter = submitter;
	}

}