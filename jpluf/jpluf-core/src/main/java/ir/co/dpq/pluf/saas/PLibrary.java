package ir.co.dpq.pluf.saas;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Inheritance;
import javax.persistence.InheritanceType;
import javax.persistence.Table;

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
@Entity(name = "saas_library")
@Table(name = "saas_library")
@Inheritance(strategy = InheritanceType.SINGLE_TABLE)
public class PLibrary {

	public static final int MODE_DEBUG = 1;
	public static final int MODE_RELESE = 2;

	public static final int TYPE_JAVASCRITP = 1;
	public static final int TYPE_CSS = 2;

	@Column(name = "id")
	private Long id;

	@Column(name = "mode")
	private Integer mode;

	@Column(name = "type")
	private Integer type;

	@Column(name = "name")
	private String name;

	@Column(name = "version")
	private String version;

	@Column(name = "description")
	private String description;

	@Column(name = "path")
	private String path;

	@Column(name = "creation_dtime")
	Date creation;

	@Column(name = "modif_dtime")
	Date modification;

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public Integer getMode() {
		return mode;
	}

	public void setMode(Integer mode) {
		this.mode = mode;
	}

	public Integer getType() {
		return type;
	}

	public void setType(Integer type) {
		this.type = type;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getVersion() {
		return version;
	}

	public void setVersion(String version) {
		this.version = version;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getPath() {
		return path;
	}

	public void setPath(String path) {
		this.path = path;
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
	
}
