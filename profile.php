<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


if(isset($_SESSION['username'])){
  
  include "includes/db.php";
  include "includes/class.user.php";
  include "includes/class.tweet.php";

  $userob = new user();
  $tweetob = new tweet();


  $username="";
  if(isset($_GET['username'])) 
    $username = trim($_GET['username']);
  else
    $username = $_SESSION['username'];

  $row = $userob->getUserDetails($username, $con);

  $title = $row['first_name']."'s Profile";
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
                  <li><a href="profile.php">Hello, <?php echo $userob->getLoggedin($_SESSION['username'], $con) ?></a></li>
                  <li><a href="logout.php">Logout</a></li>
                </ul>
            </div><!--/.nav-collapse -->
          </div>
      </nav>

      <div class="container">
        <div class="row">
          <div class="col-sm-3 profileimage">
            <?php 
              $image_show = $row['picture'];
              echo '<img style="width:100%" src="data:image;base64,'.base64_encode($image_show).'" />';
            ?>
          </div>
          <div class="col-xs-9 text-left">
            <h1> <?php echo $row['first_name']." ".$row['last_name']; ?></h1>

            <div class="col-xs-3">
              <h4> Followers </h4>
              <?php echo $userob->getNoFollowers($username, $con); ?>
            </div>
            <div class="col-sm-3">
              <h4> Following </h4>
              <?php echo $userob->getNoFollows($username, $con); ?>
            </div>
            <div class="col-sm-3">
              <h4> Tweets </h4>
              <span class="noTweets"><?php echo $userob->getNoTweets($username, $con); ?></span>
            </div>
            <div class="col-sm-3">
              <h4> Likes </h4>
              <?php echo $userob->getNoLikes($username, $con); ?>
            </div>
            <div class="clearfix"></div>
            <?php
            if($username!=$_SESSION['username']) {
              $ifFollows = $tweetob->isFollowing($username, $_SESSION['username'], $con);
             // echo $ifFollows;
            
              ?>
              <button class="btn <?php if($ifFollows=='yes') { ?> btn-danger <?php } else { ?> btn-success <?php } ?> follow-button" onclick="followUser('<?php echo $username ?>')"><?php if($ifFollows=='yes') { ?> Unfollow <?php } else { ?> Follow <?php echo $username; } ?></button>
            <?php
         
            }
            ?>
          </div>
        </div>

        <?php 
        if($username==$_SESSION['username']) { ?>
        <div class="row">
          <div class="col-xs-12">
            <h4> Enter the message to tweet </h4>
            <p class="error"></p>
            <textarea id="tweet" class="form-control"></textarea>
            <br>
            <a class="btn btn-primary pull-right" href="#" id="tweetButton"> Tweet </a>
          </div>
        </div>
        <?php } ?>

        <div class="row">
          <h4> Tweets & Retweets </h4>
          <div class="col-xs-12 tweets">
            <?php
            $tweets_user = $userob->getUserTweets($username, $con);
            $num_tweets = $tweets_user->num_rows;
            if($num_tweets>0) {
            while($row = $tweets_user->fetch_assoc()) {
              $type = $row['type'];
          

            ?>
            <div class="tweet" <?php if($type==2) { ?> style="color: blue" <?php } ?>>
              <div class="col-sm-1">
                <?php 
                $image_show2 = $row['pic'];
                echo '<img style="width:100%" src="data:image;base64,'.base64_encode($image_show2).'" />';
                ?>
              </div>
              <div class="col-sm-11">
              <?php
                if($type==1) { ?>
                  <p class="lead"> <?php echo $row['tweet_text'] ?> </p>
                  <small> <?php echo date('m/d/Y', strtotime($row['date_tweet'])); ?></small>
                <?php
             
            }
            elseif($type==2){
              ?>
              <small><?php echo $username. " retweeted this tweet from ".$row['username_tweet']. " on ".date('m/d/Y',strtotime($row['date_tweet'])); ?></small>
              <p class="lead"> <?php echo $row['tweet_text'] ?> </p>
              <small> <?php echo date('m/d/Y',strtotime($row['date_original'])) ?></small>
              <?php
            }

            ?>
             </div>

             <div class="clearfix"></div>
            </div>

            <?php
            }
          }
            ?>
          </div>
        </div>


      </div>

      <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
      <script>
        $('#tweetButton').click(function(e){
          var tweetMessage = $('#tweet').val();

          if($.trim(tweetMessage)==""){
            $('.error').html("please enter something to tweet");
          }
          else{
            $.ajax({
              method: "POST",
              url: "includes/functions.php",
              data: {tweetMessage: tweetMessage, option: "tweetforuser"}
            }).done(function(msg){
              var message = msg.split("-");

              if($.trim(message[0])=='yayy'){

                var pimage = $('.profileimage').html();

                var today = new Date();
                var todayDate = today.getMonth()+1+"/"+today.getDate()+"/"+today.getFullYear();
                $('.tweets').prepend('<div class="tweet"><div class="col-sm-1">'+pimage+'</div><div class="col-sm-11"><p class="lead">'+tweetMessage+'</p><small>'+todayDate+'</small></div><div class="clearfix"></div>');  
                $('.error').html(message[0]+'. your tweet has been successfully posted.');
                $('#tweet').val("");

                $('.noTweets').html(message[1]);

              }
              else{
                $('.error').html('there was an error, try again');
              }

            });
          }
        });

        function followUser(following){

         // alert(following);
          $.ajax({
              method: "POST",
              url: "includes/functions.php",
              data: {following: following, option: "followuser"}
            }).done(function(msg){
              //alert($('.follow-button').html());
              if($.trim($('.follow-button').html()) == 'Unfollow'){
               // alert(1);
                $('.follow-button').html('Follow '+following);
                $('.follow-button').removeClass('btn-danger');
                $('.follow-button').addClass('btn-success');
              }
              else{
              $('.follow-button').html('Unfollow');
                $('.follow-button').removeClass('btn-success');
                $('.follow-button').addClass('btn-danger');

              }

            });
        }
      </script>


    </body>
  </html>
  <?php
}
else{
  header("Location:login.php");
}

?>