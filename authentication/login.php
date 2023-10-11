<?php 

session_start();

	include("connection.php");
	include("functions.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$email = $_POST['email'];
		$password = $_POST['password'];

		if(!empty($email) && !empty($password) && !is_numeric($email))
		{

			//read from database
			$query = "select * from users where email = '$email' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					$decPassword = decryption($user_data['password']);

					if ($decPassword === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			echo "wrong email or password!";
		}
		else
			echo "wrong email or password!";
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>

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
			<div style="font-size: 20px;margin: 10px;color: white;">Login</div>

			<input id="text" type="text" name="email" required placeholder="Enter your email"> <br><br>
			<input id="text" type="password" name="password" required placeholder="Enter your password"><br><br>

			<!-- CAPTCHA -->
			<div class="g-recaptcha" data-sitekey="6Lfhg44oAAAAAAZLqqWBS-7uoj7Jawo_I7i40o9n"></div> <br>

			<input id="button" type="submit" value="Login"><br>
			<br>Don't have an account?<br>
			<a href="signup.php">Click to Register</a><br>
		</form>
	</div>
</body>
</html>