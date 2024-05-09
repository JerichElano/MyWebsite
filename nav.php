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
            <p>Username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="btn">Logout</a>
        <?php else : ?>
            <p>Please log in to enjoy shopping</p>
            <a href="login.php" class="btn">Login</a>
        <?php endif; ?>
    </div>
</header>

<script src="assets/js/btn-on-clk.js"></script>