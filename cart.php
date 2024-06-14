<?php

@include 'config.php';

session_start();

$account_id = $_SESSION['account_id'];

if(!isset($account_id)){
   header('location:login.php');
}

if (isset($_POST['delete_selected']) && isset($_POST['selected_products'])) {
    $delete_pids = $_POST['selected_products'];
    $pids = implode(",", array_map('intval', $delete_pids));
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid IN ($pids) AND account_id = '$account_id'") or die('query failed');
    header('location:cart.php');
    exit();
}

if (isset($_POST['checkout_selected']) && isset($_POST['selected_products'])) {
    $_SESSION['selected_products'] = $_POST['selected_products'];
    header('location:checkout.php');
    exit();
}

if (isset($_POST['checkout_all'])) {
    $query = "SELECT * FROM `cart` WHERE account_id = '$account_id'";
    $result = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));

    // Initialize an array to store selected product IDs
    $selected_pids = array();

    // Fetch the results and store the product IDs in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $selected_pids[] = $row['pid'];
    }

    // Check if any products were selected
    if (!empty($selected_pids)) {
        $_SESSION['selected_products'] = $selected_pids;
        header('Location: checkout.php');
        exit();
    } else {
        $_SESSION['messages'][] = 'Your cart is empty add to cart now!!';
        header('Location: index.php');
        exit();
    }
}

if (isset($_POST['continue_shopping'])) {
    header('location: index.php');
    exit();
}

// if(isset($_GET['delete_all'])){
//     $delete_ids = $_GET['delete_ids'];
//     $ids = implode(",", array_map('intval', $delete_ids));
//     mysqli_query($conn, "DELETE FROM `cart` WHERE id IN ($ids) AND account_id = '$account_id'") or die('query failed');
//     header('location:cart.php');
// }

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
            <h3>Shopping <strong style="color: #dd3157;">Cart</strong></h3>
        </section>

        <section class="shopping-cart">

            <h1 class="title">Products added</h1>

            <form class="container" action="" method="post">
                <div class="product-list">

                    <?php
                        $grand_total = 0;
                        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
                        if(mysqli_num_rows($select_cart) > 0){
                            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                    ?>
                    <li class="product-item">
                        <div class="product-card" tabindex="0">

                            <figure class="card-banner">
                                <img src="./assets/img/uploaded-img/<?php echo $fetch_cart['image']; ?>" width="312" height="350" loading="lazy" alt="Product Image" class="image-contain">

                                <div class="check">
                                    <label class="checkbox">
                                        <input type="checkbox" name="selected_products[]" value="<?php echo $fetch_cart['pid']; ?>" /> <span></span>
                                    </label>
                                </div>

                                <ul class="card-action-list">
                                        <input type="hidden" name="pid" value="<?php echo $fetch_cart['pid']; ?>">
                                        <input type="hidden" name="account_id" value="<?php echo $fetch_cart['account_id']; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $fetch_cart['name']; ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $fetch_cart['price']; ?>">
                                        <input type="hidden" name="product_image" value="<?php echo $fetch_cart['image']; ?>">

                                        <li class="card-action-item">
                                            <a type="submit" name="buy_now" class="card-action-btn" aria-labelledby="card-label-2" href="#">
                                                <ion-icon name="heart-outline"></ion-icon>
                                            </a>
                                            <div class="card-action-tooltip" id="card-label-2">Buy Now</div>
                                        </li>
                                </ul>
                            </figure>

                            <div class="card-content">

                                <h3 class="h3 card-title">
                                    <a href="#"><?php echo $fetch_cart['name']; ?></a>
                                </h3>

                                <data class="card-price" value="<?php echo $fetch_cart['price']; ?>">P<?php echo $fetch_cart['price']; ?></data>
                                <div class="quantity-container">
                                    <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>" name="cart_quantity" class="qty">
                                    <input type="submit" value="update" class="btn" name="update_quantity">
                                </div>
                                
                                <div class="sub-total"> Sub-total : <strong>P<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></strong> </div>
        
                            </div>
                        </div>
                    </li>
                    <?php
                    $grand_total += $sub_total;
                        }
                    }else{
                        echo '<p class="empty">your cart is empty</p>';
                    }
                    ?>
                </div>

                <div class="more-btn">
                    <button type="submit" name="checkout_selected" class="btn <?php echo ($grand_total > 1)?'':'disabled' ?>">Checkout Selected</button>
                    <button type="submit" name="delete_selected" class="btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('delete selected from cart?');">Delete Selected</button>
                </div>
            

                <div class="cart-total">
                    <p>Total Price : <strong style="color: var(--salmon)">P<?php echo $grand_total; ?></strong></p>
                    <button type="submit" name="continue_shopping" class="btn">Continue Shopping</button>
                    <button type="submit" name="checkout_all" class="btn  <?php echo ($grand_total > 1)?'':'disabled' ?>">Checkout All</button>
                </div>
            </form>

        </section>

    </div>

<script src="assets/js/script.js"></script>

<!-- Ionicon link -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
