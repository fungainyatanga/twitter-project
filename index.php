<?php
	$title = "Twitter";
	include "includes/header.php";
?>
	
    		<header>
				<h1><a href="index.php"> Twitter</a> </h1>
			</header>
		<div class="container">
			
            <h2> What's happening in the world and what people are talking about right now! </h2>
			<section class="row buttons">
				<div class="col-sm-6">
					<a href="login.php" class="btn btn-success btn-lg"> Login to Tweet </a>
				</div>
				<div class="col-sm-6">
					<a href="register.php" class="btn btn-primary btn-lg"> Register Now</a>
				</div>
			</section>
			<section class="row tweets">
				<h2> Latest Tweets</h2>
				<div class="tweet">
					<img src="http://via.placeholder.com/100x100" class="pull-left" />
					<h4>Name</h4>
					<p>this is the first tweet </p>
				</div>
				<div class="clearfix"></div>
				<div class="tweet">
					<img src="http://via.placeholder.com/100x100" class="pull-left" />
					<h4>Name</h4>
					<p>this is the second tweet </p>
				</div>
				<div class="clearfix"></div>
				<div class="tweet">
					<img src="http://via.placeholder.com/100x100" class="pull-left" />
					<h4>Name</h4>
					<p>this is the third tweet </p>
				</div>
			</section>
		</div>
	</body>
</html>