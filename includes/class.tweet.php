<?php
class tweet{
	function tweetForUser($tweetMessage, $dateTweet, $username,$con){
		$sql = "insert into tweets(content, date_time, tweet_username) values ('$tweetMessage', '$dateTweet', '$username')";

		if($con->query($sql) === TRUE){
			$sqlCount = "select * from tweets where tweet_username = '$username'";
			$res = $con->query($sqlCount);
			$numTweets =  $res->num_rows;

			return "yayy-".$numTweets;
		}
		else{
			return "error";
		}
	}
	function followUser($following, $follower, $dateFollow, $con){

		$isFollowing = $this->isFollowing($following, $follower, $con);
		if($isFollowing=='yes') {
			$sql = "delete from follows where follower_username='$follower' and follow_username='$following'";
			$con->query($sql);
		}
		else{
		$sql = "insert into follows(follow_username, follower_username, date_time) values ('$following', '$follower', '$dateFollow')";
		
			if($con->query($sql) === TRUE){
				return "success";
			}
			else{
				return "error";
			}
		}
	}

	function isFollowing($following, $follower, $con){
		$sql = "select * from follows where follower_username='$follower' and follow_username='$following'";
		
		$res = $con->query($sql);
		$num = $res->num_rows;

		if ($num>0) {
			return "yes";
		}
		else{
			return "no";
		}
	}


	function noLikes($tweetid, $con){
		$sql = "select * from likes where like_tweet_id='$tweetid'";
		$res = $con->query($sql);
		$num = $res->num_rows;

		return $num;
	}

	function noRetweets($tweetid, $con){
		$sql = "select * from retweets where retweet_tweet_id='$tweetid'";
		$res = $con->query($sql);
		$num = $res->num_rows;

		return $num;
	}

	function likeTweet($username, $tweetid, $con){

		$numLikes = $this->noLikes($tweetid, $con);

		$sql = "select * from likes where like_username='$username' and like_tweet_id='$tweetid'";
		$res = $con->query($sql);
		$num = $res->num_rows;

		if($num>0){
			$sqlDelete = "delete from likes where like_username='$username' and like_tweet_id='$tweetid'";
			if($con->query($sqlDelete)===TRUE){
				$numLikes = $numLikes-1;
				return "deleted-".$numLikes;
			}
		}
		else{

			$datetime = date('Y-m-d H:i:s');
			$sqlInsert = "insert into likes values ('$username', '$tweetid', '$datetime')"; 
			if($con->query($sqlInsert)===TRUE){
				$numLikes = $numLikes+1;
				return "inserted-".$numLikes;
			}
		}
	}


	function retweetTweet($username, $tweetid, $con){

		$numRetweets = $this->noRetweets($tweetid, $con);

		$sql = "select * from retweets where retweet_username='$username' and retweet_tweet_id='$tweetid'";
		$res = $con->query($sql);
		$num = $res->num_rows;

		if($num>0){
			$sqlDelete = "delete from retweets where retweet_username='$username' and retweet_tweet_id='$tweetid'";
			if($con->query($sqlDelete)===TRUE){
				$numRetweets = $numRetweets-1;
				return "deleted-".$numRetweets;
			}
		}
		else{

			$datetime = date('Y-m-d H:i:s');
			$sqlInsert = "insert into retweets values ('$username', '$tweetid', '$datetime')"; 
			if($con->query($sqlInsert)===TRUE){
				$numRetweets = $numRetweets+1;
				return "inserted-".$numRetweets;
			}
		}
	}
}
?>