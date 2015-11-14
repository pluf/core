package ir.co.dpq.pluf.jayab;

public class PVote {

	private long id;
	private long voter; // user id
	private long location; // location id
	private boolean like;

	public PVote() {
		like = false;
	}

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public long getVoter() {
		return voter;
	}

	public void setVoter(long voter) {
		this.voter = voter;
	}

	public long getLocation() {
		return location;
	}

	public void setLocation(long location) {
		this.location = location;
	}

	public boolean isLike() {
		return like;
	}

	public void setLike(boolean like) {
		this.like = like;
	}

}
