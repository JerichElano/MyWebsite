<?php

@include 'config.php';

session_start();

$account_id = $_SESSION['account_id'];

if(!isset($account_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
    header('location:cart.php');
};

if (isset($_POST['update_quantity'])) {
    $cart_account_id = $_POST['account_id'];
    $cart_pid = $_POST['pid'];
    $cart_quantity = $_POST['cart_quantity'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE account_id = ? AND pid = ?");
    $stmt->bind_param('isi', $cart_quantity, $cart_account_id, $cart_pid);

    if ($stmt->execute()) {
        $_SESSION['messages'][] = 'Cart quantity updated!';
    } else {
        die('Query failed: ' . $stmt->error);
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart | Nexus</title>

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="main.css">

</head>
<body>
    
    <?php @include 'nav.php'; ?>
    <div class="bg-container">

        <section class="heading">
            <h3>Shopping <span style="color: #dd3157;">Cart</span></h3>
        </section>

        <section class="shopping-cart">

            <h1 class="title">Products added</h1>

            <div class="box-container">

                <?php
                    $grand_total = 0;
                    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
                    if(mysqli_num_rows($select_cart) > 0){
                        while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                ?>
                <div class="box">
                    <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
                    <!-- <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a> -->
                    <img src="assets/img/uploaded-img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
                    <div class="name"><?php echo $fetch_cart['name']; ?></div>
                    <div class="price">P<?php echo $fetch_cart['price']; ?></div>
                    <form action="" method="post">
                        <input type="hidden" value="<?php echo $fetch_cart['account_id']; ?>" name="account_id">
                        <input type="hidden" value="<?php echo $fetch_cart['pid']; ?>" name="pid">
                        <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>" name="cart_quantity" class="qty">
                        <input type="submit" value="update" class="btn" name="update_quantity">
                    </form>
                    <div class="sub-total"> Sub-total : <span>P<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></span> </div>
                </div>
                <?php
                $grand_total += $sub_total;
                    }
                }else{
                    echo '<p class="empty">your cart is empty</p>';
                }
                ?>
            </div>

            <div class="more-btn">
                <a href="cart.php?delete_all" class="btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('delete all from cart?');">delete all</a>
            </div>

            <div class="cart-total">
                <p>Total Price : <span>P<?php echo $grand_total; ?></span></p>
                <a href="index.php" class="btn">Continue Shopping</a>
                <a href="checkout.php" class="btn  <?php echo ($grand_total > 1)?'':'disabled' ?>">Checkout</a>
            </div>

        </section>

    </div>




<script src="assets/js/script.js"></script>

</body>
</html>