<?php

    @include 'config.php';

    session_start();
    
    // Initialize session messages array if not already initialized
    if (!isset($_SESSION['messages'])) {
        $_SESSION['messages'] = array();
    }
    
    if (isset($_SESSION['account_id'])) {

        $account_id = $_SESSION['account_id'];

        if (isset($_POST['buy_now'])) {

            $_SESSION['product_id'] = $_POST['product_id'];
            $_SESSION['product_name'] = $_POST['product_name'];
            $_SESSION['product_price'] = $_POST['product_price'];
            $_SESSION['product_image'] = $_POST['product_image'];
            $_SESSION['product_quantity'] = $_POST['product_quantity'];
            // $_SESSION['product_variant'] = $_POST['product_variant'];
            // $_SESSION['product_color'] = $_POST['product_color'];
            $_SESSION['checker'] = $_POST['product_id'];
    
            header('location:checkout.php');
            exit;
    
        };
    
        if (isset($_POST['add_to_cart'])) {
            
            if (!isset($account_id)) {
                header('Location: login.php');
                exit;
            }
            
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_image = $_POST['product_image'];
            $product_quantity = $_POST['product_quantity'];
            // $product_variant = $_POST['product_variant'];
            // $product_color = $_POST['product_color'];
            
            $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND account_id = '$account_id'") or die('Query failed');
            
            if (mysqli_num_rows($check_cart_numbers) > 0) {
            
                $_SESSION['messages'][] = 'Already added to cart';
            
                // Use prepared statements to prevent SQL injection
                $stmt = $conn->prepare("UPDATE `cart` SET quantity = quantity + ? WHERE name = ? AND account_id = ?");
                $increment = 1;
                $stmt->bind_param('isi', $increment, $product_name, $account_id);
            
                if ($stmt->execute()) {
                    $_SESSION['messages'][] = 'Quantity incremented in cart';
                } else {
                    die('Query failed: ' . $stmt->error);
                }
            
                $stmt->close();
            } else {
                // Use prepared statements for the insert query
                $stmt = $conn->prepare("INSERT INTO `cart` (account_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sissis', $account_id, $product_id, $product_name, $product_price, $product_quantity, $product_image);
            
                if ($stmt->execute()) {
                    $_SESSION['messages'][] = 'Product added to cart';
                } else {
                    die('Query failed: ' . $stmt->error);
                }
            
                $stmt->close();
                
                // Redirect to the same page to avoid form resubmission
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            }
    
        }
    } else {
        if (isset($_POST['add_to_cart']) || isset($_POST['buy_now'])) {
        header('Location: login.php');
        exit;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>Nexus</title>

	    <link rel="icon" type="image/png" href="./assets/img/bscs.png"/>

        <link rel="stylesheet" type="text/css" href="main.css">
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous"> -->

    </head>
    <body>
    <?php include ('nav.php'); ?>
        <section class="section product" style="background-color: #FFF8F3;">
                <div class="container">

                    <h2 class="h2 section-title">Fit for you Products</h2>

                    <ul class="filter-list">

                        <li>
                            <button data-name="all" class="filter-btn  active">All</button>
                        </li>

                        <li>
                            <button data-name="budget" class="filter-btn">Budget</button>
                        </li>

                        <li>
                            <button data-name="flagship" class="filter-btn">Flagship</button>
                        </li>

                        <li>
                            <button data-name="gaming" class="filter-btn">Gaming</button>
                        </li>

                        <!-- <li>
                            <button data-name="noAvailable" class="filter-btn">Sale</button>
                        </li> -->

                    </ul>

                    <ul class="product-list">
                        <?php
                            $select_products = mysqli_query($conn, "SELECT * FROM `product` LIMIT 16") or die('query failed');
                            if (mysqli_num_rows($select_products) > 0) {
                                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                        ?>
                        
                        <li class="product-item" data-category="<?php echo strtolower($fetch_products['type']); ?>">
                                <div class="product-card" tabindex="0">

                                    <figure class="card-banner">
                                        <img src="./assets/img/uploaded-img/<?php echo $fetch_products['image']; ?>" width="312" height="350" loading="lazy" alt="Product Image" class="image-contain">
                                        <div class="card-badge">New</div>
                                        <ul class="card-action-list">
                                            <form action="" method="POST" class="card-action-form">
                                                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                                                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                                                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                                                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                                                <input type="hidden" name="product_quantity" value="1">

                                                <li class="card-action-item">
                                                    <button type="submit" name="add_to_cart" class="card-action-btn" aria-labelledby="card-label-1">
                                                        <ion-icon name="cart-outline"></ion-icon>
                                                    </button>

                                                    <div class="card-action-tooltip" id="card-label-1">Add to Cart</div>
                                                </li>

                                                <li class="card-action-item">
                                                    <button type="submit" name="buy_now" class="card-action-btn" aria-labelledby="card-label-2">
                                                        <ion-icon name="heart-outline"></ion-icon>
                                                    </button>

                                                    <div class="card-action-tooltip" id="card-label-2">Buy Now</div>
                                                </li>
                                                
                                                <li class="card-action-item">
                                                    <a href="productDetail.php?pid=<?php echo $fetch_products['id']; ?>" class="card-action-btn" aria-labelledby="card-label-3">
                                                        <ion-icon name="eye-outline"></ion-icon>
                                                    </a>

                                                    <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                                                </li>
                                            </form>
                                        </ul>
                                    </figure>

                                <div class="card-content">
                                    <div class="card-cat">
                                        <a href="#" class="card-cat-link"><?php echo $fetch_products['details']; ?></a>
                                    </div>
                                    <h3 class="h3 card-title">
                                        <a href="#"><?php echo $fetch_products['name']; ?></a>
                                    </h3>
                                    <data class="card-price" value="<?php echo $fetch_products['price']; ?>">P <?php echo $fetch_products['price']; ?></data>
                                </div>
                            </div>
                        </li>



                        <?php
                            }
                            } else {
                                echo '<p class="empty">No products added yet!</p>';
                            }
                        ?>

                    </ul>

                </div>
            </section>
        
            

        <!-- Go to top -->

        <a href="#top" class="go-top-btn" data-go-top>
            <ion-icon name="arrow-up-outline"></ion-icon>
        </a>


        <!-- Custome js -->
        <script src="./assets/js/script.js"></script>
        <script src="./assets/js/filter.js"></script>

        <!-- Ionicon link -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
            </body>
</html>
