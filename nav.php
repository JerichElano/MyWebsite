<?php
@include 'config.php';

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

<header class="header">
    <div class="logo">
        <a href="#" class="logo-link">
            <img src="assets/img/bscs.png" alt="logo">
        </a>
        <a href="#" class="logo-name">Nexus</a>

        <div class="navbar">
            <a href="#">Home</a>
            <a href="#">Brands</a>
            <a href="#">Recommended</a>
        </div>
    </div>
    <div class="navmenu">
        <a href="#"><img src="assets/img/cart.svg" alt="Cart Icon"></a>
        <a href="login.php"><img src="assets/img/account.svg" alt="Account Icon"></a>
    </div>
    <div class="account-box">
    <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
    <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
    <a href="logout.php" class="delete-btn">logout</a>
</div>
</header>
