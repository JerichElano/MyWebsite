<?php

    @include 'config.php';

    session_start();

    $account_id = $_SESSION['account_id'];

    if(isset($_SESSION['checker']) && isset($_SESSION['product_id']) && isset($_SESSION['product_name']) && isset($_SESSION['product_price']) && isset($_SESSION['product_image']) && isset($_SESSION['product_quantity'])) {
        $checker = $_SESSION['checker'];
        $product_id = $_SESSION['product_id'];
        $product_name = $_SESSION['product_name'];
        $product_price = $_SESSION['product_price'];
        $product_image = $_SESSION['product_image'];
        $product_quantity = $_SESSION['product_quantity'];
    }

    if(!isset($account_id)){
        header('location:login.php');
    };

    if (isset($_POST['order']) && !isset($_SESSION['checker'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $method = mysqli_real_escape_string($conn, $_POST['method']);
        $address = mysqli_real_escape_string($conn, $_POST['house_no'].', '. $_POST['street'].', '. $_POST['baranggay'].', '. $_POST['municipal'].' , '. $_POST['city'].' , '. $_POST['area_code']);
        $placed_on = date('d-M-Y');

        $cart_total = 0;
        $cart_products[] = '';

        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
        
        if (mysqli_num_rows($cart_query) > 0) {
            while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
                $sub_total = ($cart_item['price'] * $cart_item['quantity']);
                $cart_total += $sub_total;
            }
        }

        $total_products = implode(', ', $cart_products);

        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

        if ($cart_total == 0){
            $message[] = 'Your cart is empty!';
        } elseif (mysqli_num_rows($order_query) > 0){
            $message[] = 'Order placed already!';
        } else {
            mysqli_query($conn, "INSERT INTO `orders`(account_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$account_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
            mysqli_query($conn, "DELETE FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
            $message[] = 'Order placed successfully!';
        }

        header('location: index.php#recommended');

    } elseif (isset($_POST['order']) && isset($_SESSION['checker'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $method = mysqli_real_escape_string($conn, $_POST['method']);
        $address = mysqli_real_escape_string($conn, $_POST['house_no'].', '. $_POST['street'].', '. $_POST['baranggay'].', '. $_POST['municipal'].' , '. $_POST['city'].' , '. $_POST['area_code']);
        $placed_on = date('d-M-Y');

        $cart_total = ($product_price * $product_quantity);

        $total_products = "$product_name ($product_quantity)";

        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');
        
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order placed already!';
        } else {
            mysqli_query($conn, "INSERT INTO `orders`(account_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$account_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
            mysqli_query($conn, "DELETE FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
            $message[] = 'Order placed successfully!';
        }
        
        unset($_SESSION['checker']);
        unset($_SESSION['product_id']);
        unset($_SESSION['product_name']);
        unset($_SESSION['product_price']);
        unset($_SESSION['product_image']);
        unset($_SESSION['product_quantity']);

        header('location: index.php#recommended');
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Checkout</title>

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="main.css">
    </head>
    <body>
    
        <?php @include 'nav.php'; ?>

        <section class="heading">
            <h3>Checkout Order</h3>
        </section>

        <section class="display-order">
            <?php
                if (isset($_SESSION['checker'])) {
                    $grand_total = ($product_price * $product_quantity);
            ?>
                    <p> <?php echo $product_name?> <span>(<?php echo "P$product_price x $product_quantity" ?>)</span> </p>
            <?php
                } elseif (!isset($_SESSION['checker'])) {
                    $grand_total = 0;
                    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                            $grand_total += $total_price;

                            echo '<p>' . $fetch_cart['name'] . '<span>(P' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')</span></p>';
                        }
                    }
                } else {
                    echo '<p class="empty">Your cart is empty</p>';
                    $grand_total = 00;
                }
                
            ?>
            <div class="grand-total">Total price : <span>P<?php echo $grand_total; ?></span></div>
        </section>

        <section class="checkout">

            <form action="" method="POST">

                <h3>Place your order</h3>

                <div class="flex">
                    <div class="inputBox">
                        <span>Name :</span>
                        <input type="text" name="name" placeholder="Enter your name">
                    </div>
                    <div class="inputBox">
                        <span>Phone number :</span>
                        <input type="number" name="number" min="0" placeholder="Enter your number">
                    </div>
                    <div class="inputBox">
                        <span>Email :</span>
                        <input type="email" name="email" placeholder="Enter your email">
                    </div>
                    <div class="inputBox">
                        <span>Payment method :</span>
                        <select name="method">
                            <option value="cash on delivery">Cash on delivery</option>
                            <option value="credit card">Credit card</option>
                            <option value="paypal">Paypal</option>
                            <option value="paytm">Gcash</option>
                        </select>
                    </div>
                    <div class="inputBox">
                        <span>Address line 01 :</span>
                        <input type="text" name="house_no" placeholder="e.g. House no.">
                    </div>
                    <div class="inputBox">
                        <span>Address line 02 :</span>
                        <input type="text" name="street" placeholder="e.g.  Streen name">
                    </div>
                    <div class="inputBox">
                        <span>Baranggay :</span>
                        <input type="text" name="baranggay" placeholder="e.g. Anabu 1">
                    </div>
                    <div class="inputBox">
                        <span>Municipal :</span>
                        <input type="text" name="municipal" placeholder="e.g. Imus">
                    </div>
                    <div class="inputBox">
                        <span>City :</span>
                        <input type="text" name="city" placeholder="e.g. Cavite">
                    </div>
                    <div class="inputBox">
                        <span>Area code :</span>
                        <input type="number" min="0" name="area_code" placeholder="e.g. 4103">
                    </div>
                </div>

                <input type="submit" name="order" value="order now" class="btn">

            </form>

        </section>






        <script src="assets/js/script.js"></script>

    </body>
</html>