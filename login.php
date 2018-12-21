<?php
session_start(); //this needs to exist when using $_SESSION

if(isset($_SESSION['username']))
	header("Location: profile.php");//header function is used to redirect to other php files

include "includes/db.php";

$username = $password = "";
$usernameErr = $passErr = "";
$loginErr = "";

if(isset($_POST['btn-login'])){
	if(trim($_POST['username'])=="")
		$usernameErr = "enter username";
	else
		$username = strtolower(trim($_POST['username']));


	if(trim($_POST['password'])=="")
		$passErr = "enter password";
	else {
		$password = trim($_POST['password']);
		$passwordEncrypt = md5($password);
	}

	if($usernameErr == "" && $passErr==""){
		$sql = "select * from user where username='$username' and password='$passwordEncrypt'";
		$result = $con->query($sql);

		if($result->num_rows > 0){
			$_SESSION['username'] = $username;
			header("Location: profile.php");
		}
		else{
			$loginErr = "Error logging in, please try again";
		}
	}

}
	$title="Login to Twitter";
	include "includes/header.php";
?>

		<header>
			<h1> <a href="index.php"> Twitter </a></h1>
		</header>

		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h2> Login to Twitter</h2>
					<p class="text-center"> Enter your username and password to login </p>
					<p class="text-danger"> <?php echo $loginErr ?></p>
					<form method="post" action="login.php">
						<label>Username</label>&nbsp;<span class="error"><?php echo $usernameErr ?></span>
						<input type="text" placeholder="Enter Username" name="username" class="form-control" value="<?php echo $username ?>" />

						<label>Password </label>&nbsp;<span class="error"><?php echo $passErr ?></span>
						<input type="password" placeholder="Enter Password" name="password" class="form-control" value="<?php echo $password ?>" />

						<input type="submit" name="btn-login" class="btn btn-success form-control submit-button" />

						<br><br> don't have an account? <a href="register.php"> Register Here </a>
						<br><br>

					</form>
				</div>
			</div>
		</div>

	</body>
</html>













