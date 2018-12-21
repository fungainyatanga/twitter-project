<?php
include "includes/db.php";
$username = $email = $firstname = $lastname = $password = "";
$usernameErr = $emailErr = $fnameErr = $lnameErr = $passErr = $imgErr = "";
$registerDone="";

if(isset($_POST['btn-register'])){
	if(trim($_POST['username']) == "") //trim function removes spaces from left and right
		$usernameErr ="enter username";
	else{
		$username = strtolower(trim($_POST['username'])); //strtolower function converts string to lowercase;

		$sqlUsername = "select * from user where username='$username'";
		$result = $con->query($sqlUsername);
		if($result->num_rows >0)
			$usernameErr = "username exists";
	}


	if(trim($_POST['email']) == "") //trim function removes spaces from left and right
		$emailErr ="enter email address";
	else{
		$email = strtolower(trim($_POST['email'])); //strtolower function converts string to lowercase;

		$sqlEmail = "select * from user where email='$email'";
		$resultEmail = $con->query($sqlEmail);
		if($resultEmail->num_rows >0)
			$emailErr = "email exists";
	}

	if(trim($_POST['firstname'])=="")
		$fnameErr = "enter first name";
	else
		$firstname = ucfirst(strtolower(trim($_POST['firstname']))); //ucfirst is going to convert first letter of the string to uppercase

	if(trim($_POST['lastname'])=="")
		$lnameErr = "enter last name";
	else
		$lastname = ucfirst(strtolower(trim($_POST['lastname']))); //ucfirst is going to convert first letter of the string to uppercase


	if(trim($_POST['password'])=="")
		$passErr = "enter password";
	else {
		$password = trim($_POST['password']); 
		$passwordEncrypt = md5($password);//md5 is used to encrypt the password
	}


	if(getimagesize($_FILES['profileimage']['tmp_name']) !== false){
		$fileLoc = $_FILES['profileimage']['tmp_name'];
		$imgContent = addslashes(file_get_contents($fileLoc)); // file_get_contents gets the contents of the image to be uploaded to the database
	}
	else
		$imgErr = "upload an image";


	if($fnameErr =="" && $lnameErr=="" && $emailErr=="" && $usernameErr=="" && $passErr=="" && $imgErr==""){

		$sql = "insert into user(username, email, first_name, last_name, picture, password) values ('$username', '$email', '$firstname', '$lastname', '$imgContent', '$passwordEncrypt')";

		if($con->query($sql) === TRUE){
			$registerDone = "New user added successfully";
			$username=$email=$firstname=$lastname=$password="";
		}
		else{
			echo "Error ".$con->error;
		}

	}


}
$title = "Register to Twitter";
include "includes/header.php";
?>
		<header>
			<h1> <a href="index.php"> Twitter </a></h1>
		</header>

		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h2> Register to Twitter </h2>
					<p class="text-center"> Fill the following fields to create a twitter account </p>
					<p class="text-success"> <?php echo $registerDone ?></p>
					<form method="post" action="register.php" enctype="multipart/form-data"> <!-- enctype is needed when submitting images using the form -->

						<label>Username </label>&nbsp;<span class="error"><?php echo $usernameErr ?></span>
						<input type="text" placeholder="Enter Username" name="username" class="form-control" value="<?php echo $username ?>" />

						<label>Email Address </label>&nbsp;<span class="error"><?php echo $emailErr ?></span>
						<input type="text" placeholder="Enter Email Address" name="email" class="form-control" value="<?php echo $email ?>" />

						<label>First Name </label>&nbsp;<span class="error"><?php echo $fnameErr ?></span>
						<input type="text" placeholder="Enter First Name" name="firstname" class="form-control" value="<?php echo $firstname ?>" />

						<label>Last Name </label>&nbsp;<span class="error"><?php echo $lnameErr ?></span>
						<input type="text" placeholder="Enter Last Name" name="lastname" class="form-control" value="<?php echo $lastname ?>" />

						<label>Password </label>&nbsp;<span class="error"><?php echo $passErr ?></span>
						<input type="password" placeholder="Enter Password" name="password" class="form-control" value="<?php echo $password ?>" />

						<label> Upload Image </label>&nbsp;<span class="error"><?php echo $imgErr ?></span>
						<input type="file" name="profileimage" />

						<input type="submit" name="btn-register" class="btn btn-success form-control submit-button" />

						<br><br>already have an account <a href="login.php"> Login Here </a>
						<br><br>

					</form>
				</div>
			</div>
		</div>
	</body>
</html>