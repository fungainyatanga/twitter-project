<?php
session_start();
if(isset($_SESSION['username'])){

include "includes/db.php";
include "includes/class.user.php";
include "includes/class.tweet.php";

$userob = new user();
$tweetob = new tweet();

$username = $_SESSION['username'];

$row = $userob->getUserDetails($username, $con);
$title = $row['first_name']."'s News Feed";

include "includes/header.php";
?>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
      <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
           <span class="sr-only">Toggle navigation</span>
           <span class="icon-bar"></span>
           <span class="icon-bar"></span>
           <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Twitter</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="newsfeed.php">News Feed</a></li>
          <li><a href="profile.php">Hello, <?php echo $userob->getLoggedin($username, $con) ?></a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
    </div><!--/.nav-collapse -->
  </div>
  </nav>
  <div class="container">
  	<div class="row">
  		<div class="col-sm-12">
  			<h1> News Feed </h1>
  			<div class="col-xs-12 tweets">
  			<?php
  				$followTweets = $userob->getFollowTweets($username, $con);
  				while($row = $followTweets->fetch_assoc()){
  					?>

  					<div class="tweet row">
  						<div class="col-xs-1">
  							<?php
  							$image_show = $row['picture'];
  							 echo '<img style="width:100%" src="data:image;base64,'.base64_encode($image_show).'" />';
  							?>
  						</div>
  						<div class="col-xs-11">
  							<a href="profile.php?username=<?php echo $row['tweet_username'] ?>"><h4><?php echo $row['first_name']." ".$row['last_name']; ?></h4></a>
  							<p class="lead"><?php echo $row['content'] ?></p>
  							<small><?php echo date("m/d/Y", strtotime($row['date_time'])) ?></small>

  							<div class="actions">
  								<?php
  								$isliked = $userob->isLiked($username, $row['tweet_id'], $con); ?>
  								<a href="#" id="<?php echo $row['tweet_id'] ?>" class="link <?php if($isliked>0) { ?> unlike <?php } ?>"> 
  									<?php if($isliked>0) echo "Unlike"; else echo "Like"; ?> 
  								</a> 
  								<span id="nolikes-<?php echo $row['tweet_id'] ?>">(<?php echo $tweetob->noLikes($row['tweet_id'], $con); ?>)</span>



  								<?php
  								$isretweeted = $userob->isRetweeted($username, $row['tweet_id'], $con); ?>
  								<a href="#" id="retweet-<?php echo $row['tweet_id'] ?>" class="retweet <?php if($isretweeted>0) { ?> unlike <?php } ?>"> 
  									<?php if($isretweeted>0) echo "Unretweet"; else echo "Retweet"; ?> 
  								</a> 
  								<span id="noretweets-<?php echo $row['tweet_id'] ?>">(<?php echo $tweetob->noRetweets($row['tweet_id'], $con); ?>)</span>
  							</div>
  						</div>
  					</div>
  					<?php
  				}
  			?>
  			</div>
  		</div>
  	</div>
  </div>



 <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
 <script>
 	$('.link').click(function(e){
 		e.preventDefault();

 		var tweetid = $(this).attr('id');
 		$.ajax({
 			method:"POST",
 			url:"includes/functions.php",
 			data:{likeTweetId:tweetid, option:"likeTweet"},
 			async: false
 		}).done(function(msg){
 			msg = msg.split("-");

 			if($.trim(msg[0]) == 'inserted'){
 				$('#'+tweetid).addClass('unlike');

 				$('#'+tweetid).html('Unlike');
 			}
 			else if($.trim(msg[0]) == 'deleted'){
 				$('#'+tweetid).removeClass('unlike');
 				$('#'+tweetid).html('Like');
 			}

 			$('#nolikes-'+tweetid).html('('+msg[1]+')');
 		});
 	});


 	$('.retweet').click(function(e){
 		e.preventDefault();

 		var tweetid = $(this).attr('id');
 		var tweetidsend = tweetid.split("-");
 		
 		$.ajax({
 			method:"POST",
 			url:"includes/functions.php",
 			data:{retweetTweetId:tweetidsend[1], option:"retweetTweet"},
 			async: false
 		}).done(function(msg){
 			msg = msg.split("-");

 			if($.trim(msg[0]) == 'inserted'){
 				$('#'+tweetid).addClass('unlike');

 				$('#'+tweetid).html('Unretweet');
 			}
 			else if($.trim(msg[0]) == 'deleted'){
 				$('#'+tweetid).removeClass('unlike');
 				$('#'+tweetid).html('Retweet');
 			}

 			$('#noretweets-'+tweetidsend[1]).html('('+msg[1]+')');
 		});
 	});
 </script>
  </body>
  </html>
  <?php
	}
	else{
  		header("Location:login.php");
	}
?>