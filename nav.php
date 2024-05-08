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
<script>
document.addEventListener("DOMContentLoaded", function() {
   document.body.addEventListener("click", function(event) {
      // Check if the click was outside of any message
      if (!event.target.closest(".message")) {
         // Remove all message elements
         var messages = document.querySelectorAll(".message");
         messages.forEach(function(message) {
            message.remove();
         });
      }
   });
});
</script>
<style>
    .account-box {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
    }
    .container-log-btn {
        float: right;
        display: flex;
        align-items: center;
        height: 8vh;
        width: 80%;
        flex-wrap: wrap;
        padding-top: 10px;
    }

    .log-btn {
        font-size: 15px;
        line-height: 1.5;
        color: #fff;
        text-transform: uppercase;
        height: 50px;
        border-radius: 25px;
        background: #dd3157;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 25px;
        text-decoration: none;
    
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
    }
    
    .login-btn:hover {
        background: #333333;
    }
</style>

<header class="header">
    <div class="logo">
        <a href="#" class="logo-link">
            <img src="assets/img/bscs.png" alt="logo">
        </a>
        <a href="index.php" class="logo-name">Nexus</a>

        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="index.php#brands">Smartphones</a>
            <a href="index.php#recommended">Recommended</a>
        </div>
    </div>
    <div class="navmenu">
        <a href="cart.php"><img src="assets/img/cart.svg" alt="Cart Icon"></a>
        <a href="#" id="account-icon"><img src="assets/img/account.svg" alt="Account Icon"></a>
    </div>
    <div class="account-box" style="display: none;">
        <?php if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) : ?>
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                <div class="container-log-btn">
                    <a href="logout.php" class="log-btn">logout</a>
                </div>
        <?php else : ?>
                <p>Please log in to enjoy shopping</p>
                <div class="container-log-btn">
                    <a class="log-btn" href="login.php">Log in now</a>
                </div>
        <?php endif; ?>
    </div>
</header>

<script src="assets/js/btn-on-clk.js"></script>