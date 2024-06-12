<?php
@include('config.php');
session_start();

if(isset($_POST['login_submit'])){
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
                header('location:admin-page.php');
            } elseif($row['account_type'] == 'user'){
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['account_id'] = $row['account_id'];
                header('location:index.php');
            }
        } else {
            $message[] = 'Incorrect password!';
        }
    } else {
        $message[] = 'User not found!';
    }
}

if(isset($_POST['signup_submit'])){
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($conn, $filter_name);
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($conn, $filter_email);
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $pass = mysqli_real_escape_string($conn, md5($filter_pass));
    $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
    $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));

    $select_users = mysqli_query($conn, "SELECT * FROM `account` WHERE email = '$email'") or die('query failed');
    if(mysqli_num_rows($select_users) > 0){
        $message[] = 'User already exists!';
    } else {
        if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
        } else {
            mysqli_query($conn, "INSERT INTO `account`(name, email, password, account_type, account_creation) VALUES('$name', '$email', '$pass', 'user', NOW())") or die('query failed');
            $message[] = 'Registered successfully!';
            header('location:login.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="./assets/img/bscs.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Login & Signup | Nexus</title>
</head>
<body>
	<a href="index.php" class="container-back-btn">
		<button class="back-button">Back</button>
	</a>
    <div class="container" id="container">
        
        <div class="form-container sign-up">
            <form method="post">
                <h1>Create Account</h1>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="pass" placeholder="Password" required>
                <input type="password" name="cpass" placeholder="Confirm Password" required>
                <input type="submit" name="signup_submit" value="Sign Up" class="login-form-btn">
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post">
                <h1>Sign In</h1>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="pass" placeholder="Password" required>
                <input type="submit" name="login_submit" value="Sign In" class="login-form-btn">
            </form>
            <?php
            if(isset($message)){
                foreach($message as $message){
                    echo '<div class="message"><span>'.$message.'</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
                }
            }
            ?>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome to NEXUS</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
					<div class="nexus-logo js-tilt" data-tilt>
						<img src="assets/img/bscs.png" alt="nexus-logo">
					</div>
                    <h1>Welcome Back!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/js/log.js"></script>
</body>
</html>
