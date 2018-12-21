<?php
class user{
	
	function getUserDetails($username, $con){
		$sql = "select * from user where username='$username'";
		
		$result = $con->query($sql);
		$row = $result->fetch_assoc();

		//$fullname = $row['first_name']." ".$row['last_name'];
		return $row;
	}


	function getLoggedin($username, $con){
		$sql = "select first_name, last_name from user where username='$username'";
		
		$result = $con->query($sql);
		$row = $result->fetch_assoc();

		$fullname = $row['first_name']." ".$row['last_name'];
		return $fullname;
	}

	function getNoFollowers($username, $con){
		$sql = "SELECT * from follows where follow_username='$username'";
		$result = $con->query($sql);
		return $result->num_rows;
	}

	function getNoFollows($username, $con){
		$sql = "SELECT * from follows where follower_username='$username'";
		$result = $con->query($sql);
		return $result->num_rows;
	}

	function getNoTweets($username, $con){
		$sql = "SELECT * from tweets where tweet_username='$username'";
		$result = $con->query($sql);
		return $result->num_rows;
	}

	function getNoLikes($username, $con){
		$sql = "SELECT * from likes where like_username='$username'";
		$result = $con->query($sql);
		return $result->num_rows;
	}

	function getUserTweets($username, $con){
		$sql="select user.picture as pic, content as tweet_text,
			date_time as date_tweet, 
			tweet_username as username_tweet, 
			'test' as date_original, 1 as type
			from tweets, user
			where tweets.tweet_username=user.username and tweet_username='$username'
			UNION
			select user.picture as pic, tweets.content as tweet_text,
			retweets.date_time as date_tweet, 
			tweets.tweet_username as username_tweet, 
			tweets.date_time as date_original, 2 as type
			from tweets INNER JOIN retweets
			ON tweets.tweet_id = retweets.retweet_tweet_id
			INNER JOIN user ON tweets.tweet_username = user.username
			WHERE retweets.retweet_username='$username'
			order by date_tweet DESC
			";
		//echo $sql;
		$result = $con->query($sql);
		return $result;
	}


	function getFollowTweets($username, $con){
		$sql = "select tweets.tweet_id, tweets.content, tweets.date_time, tweets.tweet_username, user.picture, user.first_name, user.last_name from user, tweets, follows WHERE
user.username = tweets.tweet_username and tweets.tweet_username = follows.follow_username and follows.follower_username='$username' order by tweets.date_time DESC";
	
	$result = $con->query($sql);
	return $result;
	}


	function isLiked($username, $tweetid, $con){
		$sql = "select * from likes where like_username='$username' and like_tweet_id='$tweetid'";
		$res = $con->query($sql);
		$num = $res->num_rows;
		return $num;
	}
	function isRetweeted($username, $tweetid, $con){
		$sql = "select * from retweets where retweet_username='$username' and retweet_tweet_id='$tweetid'";
		$res = $con->query($sql);
		$num = $res->num_rows;
		return $num;
	}



}


?>