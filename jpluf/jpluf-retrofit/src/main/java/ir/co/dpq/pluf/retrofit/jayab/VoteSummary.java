package ir.co.dpq.pluf.retrofit.jayab;

import com.google.gson.annotations.SerializedName;

public class VoteSummary {


	@SerializedName("like")
	private long likes;

	@SerializedName("dislike")
	private long dislikes;

	public long getLikes() {
		return likes;
	}

	public void setLikes(long likes) {
		this.likes = likes;
	}

	public long getDislikes() {
		return dislikes;
	}

	public void setDislikes(long dislikes) {
		this.dislikes = dislikes;
	}

}
