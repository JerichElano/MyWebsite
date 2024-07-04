<?php
if(isset($_SESSION['messages'])){
   foreach($_SESSION['messages'] as $message){
      echo '
      <div class="message-box">
         <p>'.$message.'</p>
      </div>
      ';
   }
   unset($_SESSION['messages']);
//    echo '<script>setTimeout(function() { window.location.href = "index.php"; }, 3000);</script>';
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
   document.addEventListener("click", function(event) {
      // Check if the click was outside of any message box
      if (!event.target.closest(".message-box")) {
         // Remove all message box elements
         var messageBoxes = document.querySelectorAll(".message-box");
         messageBoxes.forEach(function(messageBox) {
            messageBox.remove();
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
            <a href="index.php#Smartphones">Smartphones</a>
            <a href="index.php#Featured">Featured</a>
        </div>
    </div>
    <div class="navmenu">
        <a href="cart.php"><img src="assets/img/cart.svg" alt="Cart Icon"></a>
        <a href="#" id="account-icon"><img src="assets/img/account.svg" alt="Account Icon"><span class="badge badge-light">4</span></a>
    </div>
    <div class="account-box" style="display: none;">
        <?php if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) : ?>
            <p>Username : <strong><?php echo $_SESSION['user_name']; ?></strong></p>
            <p>Email : <strong><?php echo $_SESSION['user_email']; ?></strong></p>
            <a href="logout.php" class="btn">Logout</a>
        <?php else : ?>
            <p>Please log in to enjoy shopping</p>
            <a href="login.php" class="btn">Login</a>
        <?php endif; ?>
    </div>
</header>

<script src="assets/js/btn-on-clk.js"></script>