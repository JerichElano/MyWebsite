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

if (isset($_SESSION['account_id'])) {
    $account_id = $_SESSION['account_id'];

    // SQL query to calculate the total number of products in the cart for the given account_id
    $stmt = $conn->prepare("SELECT SUM(quantity) AS total_products FROM `cart` WHERE `account_id` = ?");
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_products = $row['total_products'];

    $stmt->close();
} else {
    $total_products = '';
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

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    
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
        <a href="cart.php">
            <img src="assets/img/cart.svg" alt="Cart Icon">
            <?php if (isset($total_products) && $total_products > 0) : ?>
                <strong class="badge"><?php echo $total_products; ?></strong>
            <?php endif; ?>
        </a>
        <a href="#" id="account-icon"><img src="assets/img/account.svg" alt="Account Icon"></a>
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