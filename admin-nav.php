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
        <a href="#" class="logo-name">Nexus</a>

        <div class="navbar">
            <a href="admin-page.php#dashboard">Dashboard</a>
            <a href="admin-page.php#products">Products</a>
            <a href="admin-page.php#orders">Orders</a>
            <a href="admin-page.php#accounts">Accounts</a>
        </div>
    </div>
    <div class="navmenu">
        <a href="#" id="account-icon"><img src="assets/img/account.svg" alt="Account Icon"></a>
    </div>
    <div class="account-box" style="display: none;">
        <p>Admin name : <span><?php echo $_SESSION['admin_name']; ?></span></p>
        <p>Email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
        <a href="logout.php" class="btn">Logout</a>
        <p style="font-size: 12px;">Want to <a href="admin-signup.php" style="text-decoration: none; color: red;">register</a> new admin account?</p>
    </div>
</header>

<script src="assets/js/btn-on-clk.js"></script>