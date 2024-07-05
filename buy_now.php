<?php
session_start();
if (isset($_POST['buy_now'])) {
    $_SESSION['product_id'] = $_POST['product_id'];
    $_SESSION['product_name'] = $_POST['product_name'];
    $_SESSION['product_price'] = $_POST['product_price'];
    $_SESSION['product_image'] = $_POST['product_image'];
    $_SESSION['product_quantity'] = $_POST['product_quantity'];
    // $_SESSION['product_variant'] = $_POST['product_variant'];
    // $_SESSION['product_color'] = $_POST['product_color'];
    $_SESSION['checker'] = $_POST['product_id'];

    echo "success";
    exit;
}
?>
