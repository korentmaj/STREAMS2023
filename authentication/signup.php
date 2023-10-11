<?php 
session_start();

	include("connection.php");
	include("functions.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$email = $_POST['email'];
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		//check if username already exists
		$username_to_check = $user_name;
		$sql = "select user_name from users where user_name = '$username_to_check'";
		$result_username = $con->query($sql);

		//check if email already in use
		$email_to_check = $email;
		$sql = "select email from users where email = '$email_to_check'";
		$result_email = $con->query($sql);

		//check if email has @
		$email_valid = filter_var($email, FILTER_VALIDATE_EMAIL);

		//encrypt the password
		$password = encryption($password);

		if(!empty($user_name) && !empty($password) && !empty($email) && !is_numeric($user_name) && $result_username->num_rows == 0 && $result_email->num_rows == 0 && $email_valid) 
		{
			//save to database
			$user_id = random_num(20);
			$query = "insert into users (user_id,user_name,password,email) values ('$user_id','$user_name','$password', '$email')";

			mysqli_query($con, $query);

			header("Location: login.php");
			die;
		}
		//if username exists
		else if($result_username->num_rows > 0)
			echo "Username already exists!";
		//if email already in use
		else if($result_email->num_rows > 0)
			echo "email already in use!";
		else if(!$email_valid)
			echo "email is invalid!";
		else
			echo "Please enter some valid information!";
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Register</title>

	<!-- CAPTCHA -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script type="text/javascript">
		window.addEventListener('load', () => {
		const $recaptcha = document.querySelector('#g-recaptcha-response');
		if ($recaptcha) {
			$recaptcha.setAttribute('required', 'required');
		}
		})
	</script>
		<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
		async defer>
	</script>

</head>
<body>

	<style type="text/css">
	
	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#button{

		cursor: pointer;
		padding: 10px;
		width: 100px;
		color: white;
		background-color: lightblue;
		border: none;
	}

	#box{

		background-color: grey;
		margin: auto;
		width: 300px;
		padding: 20px;
	}

	</style>

	<div id="box">
		
		<form method="post">
			<div style="font-size: 20px;margin: 10px;color: white;">Register</div>
			
			<input id="text" type="text" name="email" required placeholder="Enter your email"><br><br>
			<input id="text" type="text" name="user_name" required placeholder="Enter your username"><br><br>
			<input id="text" type="password" name="password" required placeholder="Enter your password"><br><br>
			
			<!-- CAPTCHA -->
			<div class="g-recaptcha" data-sitekey="6Lfhg44oAAAAAAZLqqWBS-7uoj7Jawo_I7i40o9n"></div> <br>
			
			<input id="button" type="submit" value="Register"><br>
			<br>Already have an account?<br>
			<a href="login.php">Click to Login</a><br>
		</form>
	</div>
</body>
</html>