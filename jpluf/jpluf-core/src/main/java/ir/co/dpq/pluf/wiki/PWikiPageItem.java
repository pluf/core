package ir.co.dpq.pluf.wiki;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.Inheritance;
import javax.persistence.InheritanceType;
import javax.persistence.Table;

@Entity(name = "wiki_page")
@Table(name = "wiki_page")
@Inheritance(strategy=InheritanceType.SINGLE_TABLE)
public class PWikiPageItem {

	@Id
	@Column(name = "page_id")
	private Long id;
	
	@Column(name = "priority")
	private Integer priority;
	
	@Column(name = "state")
	private Integer state;
	
	@Column(name = "title")
	private String title;

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public Integer getPriority() {
		return priority;
	}

	public void setPriority(Integer priority) {
		this.priority = priority;
	}

	public Integer getState() {
		return state;
	}

	public void setState(Integer state) {
		this.state = state;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

}
