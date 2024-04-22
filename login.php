<?php
@include('config.php');

session_start();

if(isset($_POST['submit'])){

	$filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
	$email = mysqli_real_escape_string($conn, $filter_email);
	$filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
	$pass = mysqli_real_escape_string($conn, md5($filter_pass));
 
	$select_users = mysqli_query($conn, "SELECT * FROM `account` WHERE email = '$email'") or die('query failed');
 
 
	if(mysqli_num_rows($select_users) > 0){
	   
	   	$row = mysqli_fetch_assoc($select_users);
 
		if($row['password'] == $pass) {
			if($row['account_type'] == 'admin'){
	
				$_SESSION['admin_name'] = $row['name'];
				$_SESSION['admin_email'] = $row['email'];
				$_SESSION['admin_id'] = $row['account_id'];
				header('location:index.php');
	
			}elseif($row['account_type'] == 'user'){
		
				$_SESSION['user_name'] = $row['name'];
				$_SESSION['user_email'] = $row['email'];
				$_SESSION['user_id'] = $row['account_id'];
				header('location:index.php');
			}
			}else{
				$message[] = 'Incorrect password!';
			}
		
 
	}	else{
	   $message[] = 'User not found!';
	}
 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login | Nexus</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="assets/img/bscs.png"/>	
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="assets/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<!--===============================================================================================-->
</head>
<body>	
	<div class="limiter">
		<div class="container-login">
			<div class="container-back-btn">
				<a href="index.php">
					<button class="back-button">
						Back
					</button>
				</a>
			</div>
			<div class="wrap-login">
				<div class="nexus-logo js-tilt" data-tilt>
					<img src="assets/img/bscs.png" alt="nexus-logo">
				</div>

				<form class="login-form validate-form" method="post">
					<span class="login-form-title">
						Login
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="email" name="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					<div class="container-login-form-btn">
						<input class="login-form-btn" type="submit" name="submit" value="Log in now">
					</div>

					<div class="text-center p-t-12">
						<?php
						if(isset($message)){
							foreach($message as $message){
								echo '
								<div class="message">
									<span>'.$message.'</span>
									<i class="fas fa-times" onclick="this.parentElement.remove();"></i>
								</div>
								';
							}
						}
						?>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="signup.php">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->	
	<script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/bootstrap/js/popper.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="assets/js/main.js"></script>

</body>
</html>