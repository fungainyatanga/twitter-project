<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";
include "class.tweet.php";

$username = $_SESSION['username'];
$tweetob = new tweet();

$option = trim($_POST['option']);

if($option=='tweetforuser'){
	$tweetMessage = trim($_POST['tweetMessage']);
	$dateTweet = date('Y-m-d H:i:s');

	$result = $tweetob->tweetForUser($tweetMessage, $dateTweet, $username, $con);
	echo $result;

}
if($option=='followuser'){
	$following = trim($_POST['following']);

	$dateFollow = date('Y-m-d H:i:s');

	$result = $tweetob->followUser($following, $username, $dateFollow, $con);
	echo $result;
}

if($option=='likeTweet'){
	$username = $_SESSION['username'];
	$likeTweetId = trim($_POST['likeTweetId']);
	$result = $tweetob->likeTweet($username, $likeTweetId, $con);
	echo $result;
}

if($option=='retweetTweet'){
	$username = $_SESSION['username'];
	$retweetTweetId = trim($_POST['retweetTweetId']);
	$result = $tweetob->retweetTweet($username, $retweetTweetId, $con);
	echo $result;
}


?>