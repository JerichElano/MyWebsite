<?php

@include 'config.php';

session_start();

$account_id = $_SESSION['account_id'];

if (!isset($account_id)) {
    header('location:login.php');
    exit();
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

    $selected_pids = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $selected_pids[] = $row['pid'];
    }

    if (!empty($selected_pids)) {
        $_SESSION['selected_products'] = $selected_pids;
        header('Location: checkout.php');
        exit();
    } else {
        $_SESSION['messages'][] = 'Your cart is empty, add to cart now!';
        header('Location: index.php');
        exit();
    }
}

if (isset($_POST['continue_shopping'])) {
    header('location: index.php');
    exit();
}

if (isset($_POST['update_quantity'])) {
    $cart_account_id = filter_var($_POST['account_id'], FILTER_SANITIZE_NUMBER_INT);
    $cart_pid = filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
    $cart_quantity = filter_var($_POST['cart_quantity'], FILTER_SANITIZE_NUMBER_INT);

    if ($cart_quantity < 1) {
        $_SESSION['messages'][] = 'Quantity must be at least 1.';
        header('location: cart.php');
        exit();
    }

    $stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE account_id = ? AND pid = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('iii', $cart_quantity, $cart_account_id, $cart_pid);

    if ($stmt->execute()) {
        $_SESSION['messages'][] = 'Cart quantity updated!';
    } else {
        die('Query failed: ' . htmlspecialchars($stmt->error));
    }

    $stmt->close();

    header('location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart | Nexus</title>
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

        <form class="container" id="cart-form" action="" method="post">
            <div class="product-list">

                <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                ?>
                <div class="product-item">
                    <div class="product-card" tabindex="0">

                        <figure class="card-banner">
                            <a href="productDetail.php?pid=<?php echo $fetch_cart['pid']; ?>">
                                <img src="./assets/img/uploaded-img/<?php echo htmlspecialchars($fetch_cart['image']); ?>" width="312" height="350" loading="lazy" alt="Product Image" class="image-contain">
                            </a>
                            <div class="check">
                                <label class="checkbox">
                                    <input type="checkbox" name="selected_products[]" value="<?php echo htmlspecialchars($fetch_cart['pid']); ?>" /> <span></span>
                                </label>
                            </div>
                        </figure>

                        <div class="card-content">
                            <h3 class="h3 card-title">
                                <a href="#"><?php echo htmlspecialchars($fetch_cart['name']); ?></a>
                            </h3>
                            <data class="card-price" value="<?php echo htmlspecialchars($fetch_cart['price']); ?>">P<?php echo htmlspecialchars($fetch_cart['price']); ?></data>
                            
                            <form action="" method="post">
                                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($fetch_cart['pid']); ?>">
                                <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($fetch_cart['account_id']); ?>">
                                <div class="quantity-container">
                                    <input type="number" min="1" value="<?php echo htmlspecialchars($fetch_cart['quantity']); ?>" name="cart_quantity" class="qty">
                                    <input type="submit" value="update" class="btn" name="update_quantity">
                                </div>
                            </form>

                            <div class="sub-total"> Sub-total : <strong>P<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></strong> </div>
                        </div>
                    </div>
                </div>
                <?php
                    $grand_total += $sub_total;
                    }
                } else {
                    echo '<p class="empty">your cart is empty</p>';
                }
                ?>
            </div>

            <div class="more-btn">
                <button type="button" id="checkout-selected" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Checkout Selected</button>
                <button type="button" id="delete-selected" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('delete selected from cart?');">Delete Selected</button>
            </div>

            <div class="cart-total">
                <p>Total Price : <strong style="color: var(--salmon)">P<?php echo $grand_total; ?></strong></p>
                <button type="button" id="continue-shopping" class="btn">Continue Shopping</button>
                <button type="button" id="checkout-all" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Checkout All</button>
            </div>
        </form>

    </section>

</div>

<script src="assets/js/script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<script>
    document.getElementById('checkout-selected').addEventListener('click', function() {
        var form = document.getElementById('cart-form');
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'checkout_selected';
        input.value = '1';
        form.appendChild(input);
        form.submit();
    });

    document.getElementById('delete-selected').addEventListener('click', function() {
        var form = document.getElementById('cart-form');
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_selected';
        input.value = '1';
        form.appendChild(input);
        form.submit();
    });

    document.getElementById('checkout-all').addEventListener('click', function() {
        var form = document.getElementById('cart-form');
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'checkout_all';
        input.value = '1';
        form.appendChild(input);
        form.submit();
    });

    document.getElementById('continue-shopping').addEventListener('click', function() {
        var form = document.getElementById('cart-form');
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'continue_shopping';
        input.value = '1';
        form.appendChild(input);
        form.submit();
    });
</script>

</body>
</html>
