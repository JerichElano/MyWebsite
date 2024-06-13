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
            $_SESSION['checker'] = "fromBuyNow";
    
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
    </head>
    <body>
        <?php include ('nav.php'); ?>

        <main class="main">
            <div class="hero-page">
                <video controls autoplay muted loop>
                    <source src="assets/vid/advertisement.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="container">
                    <h2 class="">Welcome to <strong style="color: #dd3157;">Nexus</strong></h2>
                    <p>Your Portal to Infinite Connectivity</p>
                    <a href="#recommended" class="btn nbtn btn-link">Buy now</a>
                </div>
            </div>
            
            <!-- Brand sliding -->

            <div class="brand-list">
                <div class="slider">
                    <div class="slide-track">
                        <div class="slide">
                            <img src="assets/img/brands/brand1.png" alt="Samsung Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand2.png" alt="iPhone Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand3.png" alt="Xiaomi Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand4.png" alt="Sony Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand5.png" alt="Cherry Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand6.png" alt="Vivo Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand7.png" alt="Realme Logo">
                        </div>

                        <div class="slide">
                            <img src="assets/img/brands/brand1.png" alt="Samsung Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand2.png" alt="iPhone Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand3.png" alt="Xiaomi Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand4.png" alt="Sony Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand5.png" alt="Cherry Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand6.png" alt="Vivo Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand7.png" alt="Realme Logo">
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="brands">
                <h2 id="brands">Smartphones</h2>
                <div class="brands-container">
                    <div class="row">
                        <div class="column">
                            <img src="assets/img/phones/phone1.jpg">
                            <img src="assets/img/phones/phone2.jpg">
                            <img src="assets/img/phones/phone15.jpg">
                        </div>
                        <div class="column">
                            <img src="assets/img/phones/phone8.jpg">
                            <img src="assets/img/phones/phone9.jpg">
                            <img src="assets/img/phones/phone11.jpg">
                        </div>
                        <div class="column">
                            <img src="assets/img/phones/phone4.jpg">
                            <img src="assets/img/phones/phone7.jpg">
                            <img src="assets/img/phones/phone5.jpg">
                        </div>
                        <div class="column">
                            <img src="assets/img/phones/phone16.jpg">
                            <img src="assets/img/phones/phone14.jpg">
                            <img src="assets/img/phones/phone12.jpg">
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- collection -->

            <section class="section collection">
                <div class="container">

                <ul class="collection-list has-scrollbar">

                    <li>
                    <div class="collection-card" style="background-image: url('./assets/img/phones/cta/p1.png')">
                        <h3 class="h4 card-title">Budget Smartphones</h3>

                        <a href="#" class="btn nbtn btn-secondary">
                        <span>Explore All</span>

                        <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                        </a>
                    </div>
                    </li>

                    <li>
                    <div class="collection-card" style="background-image: url('./assets/img/phones/cta/p3.png')">
                        <h3 class="h4 card-title">Flagship Smartphones</h3>

                        <a href="#" class="btn nbtn btn-secondary">
                        <span>Explore All</span>

                        <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                        </a>
                    </div>
                    </li>

                    <li>
                    <div class="collection-card" style="background-image: url('./assets/img/phones/cta/p2.png')">
                        <h3 class="h4 card-title">Gaming Smartphones</h3>

                        <a href="#" class="btn nbtn btn-secondary">
                        <span>Explore All</span>

                        <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                        </a>
                    </div>
                    </li>

                </ul>

                </div>
            </section>

            <!-- Brand sliding -->

            <div class="brand-lists">
                <div class="slider">
                    <div class="slide-track">
                        <div class="slide">
                            <img src="assets/img/brands/brand1.png" alt="Samsung Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand2.png" alt="iPhone Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand3.png" alt="Xiaomi Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand4.png" alt="Sony Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand5.png" alt="Cherry Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand6.png" alt="Vivo Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand7.png" alt="Realme Logo">
                        </div>

                        <div class="slide">
                            <img src="assets/img/brands/brand1.png" alt="Samsung Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand2.png" alt="iPhone Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand3.png" alt="Xiaomi Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand4.png" alt="Sony Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand5.png" alt="Cherry Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand6.png" alt="Vivo Logo">
                        </div>
                        <div class="slide">
                            <img src="assets/img/brands/brand7.png" alt="Realme Logo">
                        </div>
                    </div>
                    <div id="Smartphones"></div>
                </div>
            </div>

            <!-- Products -->
            
            <section class="section product">
                <div class="container">

                    <h2 class="h2 section-title">Best Sellers Products</h2>

                    <ul class="filter-list">

                        <li>
                            <button class="filter-btn  active">All</button>
                        </li>

                        <li>
                            <button class="filter-btn">Samsung</button>
                        </li>

                        <li>
                            <button class="filter-btn">Apple</button>
                        </li>

                        <li>
                            <button class="filter-btn">Redmi</button>
                        </li>

                        <li>
                            <button class="filter-btn">Oppo</button>
                        </li>

                        <li>
                            <button class="filter-btn">Cherry</button>
                        </li>

                    </ul>

                    <ul class="product-list">
                        <?php
                            $select_products = mysqli_query($conn, "SELECT * FROM `product` LIMIT 8") or die('query failed');
                            if (mysqli_num_rows($select_products) > 0) {
                                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                        ?>
                        
                            <li class="product-item">
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
                                                    <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="card-action-btn" aria-labelledby="card-label-3">
                                                        <ion-icon name="eye-outline"></ion-icon>
                                                    </a>
                                                    <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                                                </li>
                                            </form>

                                        </ul>
                                    </figure>

                                    <div class="card-content">
                                        <div class="card-cat">
                                            <a href="#" class="card-cat-link">Men</a> /
                                            <a href="#" class="card-cat-link">Women</a>
                                        </div>

                                        <h3 class="h3 card-title">
                                            <a href="#"><?php echo $fetch_products['name']; ?></a>
                                        </h3>

                                        <data class="card-price" value="<?php echo $fetch_products['price']; ?>">P<?php echo $fetch_products['price']; ?></data>
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


            <!-- CTA -->

            <section class="section cta">
                <div class="container">

                    <ul class="cta-list">

                        <li>
                        <div class="cta-card" style="background-image: url('./assets/img/phones/phone1.jpg')">
                            <p class="card-subtitle">Realme</p>

                            <h3 class="h2 card-title">The Summer Sale Off 50%</h3>

                            <a href="#" class="btn nbtn btn-link">
                            <span>Shop Now</span>

                            <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                            </a>
                        </div>
                        </li>

                        <li>
                        <div class="cta-card" style="background-image: url('./assets/img/phones/phone2.jpg')">
                            <p class="card-subtitle">Sonny</p>

                            <h3 class="h2 card-title">Makes Yourself Keep Sporty</h3>

                            <a href="#" class="btn nbtn btn-link">
                            <span>Shop Now</span>

                            <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                            </a>
                        </div>
                        </li>

                    </ul>

                </div>
                <div id="Featured"></div>
            </section>


            <!-- Special -->

            <section class="section special">
                <div class="container">

                    <div class="special-banner">
                        <h2 class="h3 banner-title">New Trend Edition</h2>
                        <video id="specialBannerVideo" autoplay muted playsinline class="background-video" style="width: 288px;">
                            <source src="./assets/vid/Featured.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <a href="#" class="btn nbtn btn-link">
                            <span>Explore All</span>
                            <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                        </a>
                    </div>

                    <div class="special-product">

                        <h2 class="h2 section-title">
                        <span class="text">Featured Phones</span>

                        <span class="line"></span>
                        </h2>

                        <ul class="has-scrollbar">

                            <li class="product-item">
                                <div class="product-card" tabindex="0">

                                <figure class="card-banner">
                                    <img src="./assets/img/featured/iphone_15_pro.png" width="312" height="350" loading="lazy"
                                    alt="Running Sneaker Shoes" class="image-contain">

                                    <div class="card-badge">New</div>

                                    <ul class="card-action-list">

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-1">
                                        <ion-icon name="cart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-1">Add to Cart</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-2">
                                        <ion-icon name="heart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-2">Buy Now</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-3">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                                    </li>

                                    </ul>
                                </figure>

                                <div class="card-content">

                                    <div class="card-cat">
                                    <a href="#" class="card-cat-link">The ultimate iPhone.</a>
                                    </div>

                                    <h3 class="h3 card-title">
                                    <a href="#">iPhone 15 Pro</a>
                                    </h3>

                                    <data class="card-price" value="180.85">P 58,541.50</data>

                                </div>

                                </div>
                            </li>

                            <li class="product-item">
                                <div class="product-card" tabindex="0">

                                <figure class="card-banner">
                                    <img src="./assets/img/featured/iphone_15.png" width="312" height="350" loading="lazy"
                                    alt="Leather Mens Slipper" class="image-contain">

                                    <div class="card-badge">New</div>
                                    
                                    <ul class="card-action-list">

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-1">
                                        <ion-icon name="cart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-1">Add to Cart</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-2">
                                        <ion-icon name="heart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-2">Buy Now</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-3">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                                    </li>

                                    </ul>
                                </figure>

                                <div class="card-content">

                                    <div class="card-cat">
                                    <a href="#" class="card-cat-link">A total powerhouse.</a>
                                    </div>

                                    <h3 class="h3 card-title">
                                    <a href="#">iPhone 15</a>
                                    </h3>

                                    <data class="card-price" value="190.85">P 46,821.60</data>

                                </div>

                                </div>
                            </li>

                            <li class="product-item">
                                <div class="product-card" tabindex="0">

                                <figure class="card-banner">
                                    <img src="./assets/img/featured/iphone_14.png" width="312" height="350" loading="lazy"
                                    alt="Simple Fabric Shoe" class="image-contain">

                                    <ul class="card-action-list">

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-1">
                                        <ion-icon name="cart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-1">Add to Cart</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-2">
                                        <ion-icon name="heart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-2">Buy Now</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-3">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                                    </li>

                                    </ul>
                                </figure>

                                <div class="card-content">

                                    <div class="card-cat">
                                    <a href="#" class="card-cat-link">As amazing as ever.</a>
                                    </div>

                                    <h3 class="h3 card-title">
                                    <a href="#">iPhone 14</a>
                                    </h3>

                                    <data class="card-price" value="160.85">P 40,961.80</data>

                                </div>

                                </div>
                            </li>

                            <li class="product-item">
                                <div class="product-card" tabindex="0">

                                <figure class="card-banner">
                                    <img src="./assets/img/featured/iphone_13.png" width="312" height="350" loading="lazy"
                                    alt="Air Jordan 7 Retro " class="image-contain">

                                    <div class="card-badge"> -25%</div>

                                    <ul class="card-action-list">

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-1">
                                        <ion-icon name="cart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-1">Add to Cart</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-2">
                                        <ion-icon name="heart-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-2">Buy Now</div>
                                    </li>

                                    <li class="card-action-item">
                                        <button class="card-action-btn" aria-labelledby="card-label-3">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        </button>

                                        <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                                    </li>

                                    </ul>
                                </figure>

                                <div class="card-content">

                                    <div class="card-cat">
                                    <a href="#" class="card-cat-link">All kinds of awesome.</a>
                                    </div>

                                    <h3 class="h3 card-title">
                                    <a href="#">iPhone 13</a>
                                    </h3>

                                    <data class="card-price" value="170.85">P 26,325.65 <del>P 35,101.40</del></data>

                                </div>

                                </div>
                            </li>

                        </ul>

                    </div>

                </div>
            </section>

            <!-- Services -->

            <section class="section service">
                <div class="container">

                <ul class="service-list">

                    <li class="service-item">
                    <div class="service-card">

                        <div class="card-icon">
                        <img src="./assets/img/services/service-1.png" width="53" height="28" loading="lazy" alt="Service icon">
                        </div>

                        <div>
                        <h3 class="h4 card-title">Free Shiping</h3>

                        <p class="card-text">
                            All orders over <span>P 30,000</span>
                        </p>
                        </div>

                    </div>
                    </li>

                    <li class="service-item">
                    <div class="service-card">

                        <div class="card-icon">
                        <img src="./assets/img/services/service-2.png" width="43" height="35" loading="lazy" alt="Service icon">
                        </div>

                        <div>
                        <h3 class="h4 card-title">Quick Payment</h3>

                        <p class="card-text">
                            100% secure payment
                        </p>
                        </div>

                    </div>
                    </li>

                    <li class="service-item">
                    <div class="service-card">

                        <div class="card-icon">
                        <img src="./assets/img/services/service-3.png" width="40" height="40" loading="lazy" alt="Service icon">
                        </div>

                        <div>
                        <h3 class="h4 card-title">Free Returns</h3>

                        <p class="card-text">
                            Money back in 30 days
                        </p>
                        </div>

                    </div>
                    </li>

                    <li class="service-item">
                    <div class="service-card">

                        <div class="card-icon">
                        <img src="./assets/img/services/service-4.png" width="40px" height="40px" loading="lazy" alt="Service icon">
                        </div>

                        <div>
                        <h3 class="h4 card-title">24/7 Support</h3>

                        <p class="card-text">
                            Get Quick Support
                        </p>
                        </div>

                    </div>
                    </li>

                </ul>

                </div>
            </section>


            <!-- Instagram Posts -->

            <section class="section insta-post">

                <ul class="insta-post-list has-scrollbar">

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-1.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-2.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-3.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-4.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-5.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-6.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-7.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                <li class="insta-post-item">
                    <img src="./assets/img/grayColorPhones/phone-8.png" width="100" height="100" loading="lazy" alt="Instagram post"
                    class="insta-post-banner image-contain">

                    <a href="#" class="insta-post-link">
                    <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>

                </ul>

            </section>

        </main>


        <!-- Footer -->

        <footer class="footer">

            <div class="footer-top section">
                <div class="footer-brand">

                    <a href="#" class="logo">
                        <img src="./assets/img/bscs.png" style="height: 40px; width: 40px;" alt="Nexus logo">
                        <h1>Nexus Philippines</h1>
                    </a>

                    <ul class="social-list">

                        <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a>
                        </li>

                        <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-pinterest"></ion-icon>
                        </a>
                        </li>

                        <li>
                        <a href="#" class="social-link">
                            <ion-icon name="logo-linkedin"></ion-icon>
                        </a>
                        </li>

                    </ul>

                </div>

                <div class="container">

                    <div class="footer-link-box">

                        <ul class="footer-list">

                            <li>
                                <p class="footer-list-title">Contact Us</p>
                            </li>

                            <li>
                                <address class="footer-link">
                                    <ion-icon name="location"></ion-icon>

                                    <span class="footer-link-text">
                                    2751 S Parker Rd, Aurora, CO 80014, Philippines
                                    </span>
                                </address>
                            </li>

                            <li>
                                <a href="tel:+557343673257" class="footer-link">
                                    <ion-icon name="call"></ion-icon>

                                    <span class="footer-link-text">+63921 049 3887</span>
                                </a>
                            </li>

                            <li>
                                <a href="mailto:footcap@help.com" class="footer-link">
                                    <ion-icon name="mail"></ion-icon>

                                    <span class="footer-link-text">nexusph@help.com</span>
                                </a>
                            </li>

                        </ul>

                        <ul class="footer-list">

                            <li>
                                <p class="footer-list-title">My Account</p>
                            </li>

                            <li>
                                <a href="#" class="footer-link">
                                    <ion-icon name="chevron-forward-outline"></ion-icon>

                                    <span class="footer-link-text">My Account</span>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="footer-link">
                                    <ion-icon name="chevron-forward-outline"></ion-icon>

                                    <span class="footer-link-text">View Cart</span>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="footer-link">
                                    <ion-icon name="chevron-forward-outline"></ion-icon>

                                    <span class="footer-link-text">New Products</span>
                                </a>
                            </li>

                        </ul>

                        <div class="footer-list">

                            <p class="footer-list-title">Opening Time</p>

                            <table class="footer-table">
                                <tbody>

                                    <tr class="table-row">
                                    <th class="table-head" scope="row">Mon - Tue:</th>

                                    <td class="table-data">8AM - 10PM</td>
                                    </tr>

                                    <tr class="table-row">
                                    <th class="table-head" scope="row">Wed:</th>

                                    <td class="table-data">8AM - 7PM</td>
                                    </tr>

                                    <tr class="table-row">
                                    <th class="table-head" scope="row">Fri:</th>

                                    <td class="table-data">7AM - 12PM</td>
                                    </tr>

                                    <tr class="table-row">
                                    <th class="table-head" scope="row">Sat:</th>

                                    <td class="table-data">9AM - 8PM</td>
                                    </tr>

                                    <tr class="table-row">
                                    <th class="table-head" scope="row">Sun:</th>

                                    <td class="table-data">Closed</td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>

                        <div class="footer-list">

                            <p class="footer-list-title">Newsletter</p>

                            <p class="newsletter-text">
                                Authoritatively morph 24/7 potentialities with error-free partnerships.
                            </p>

                            <form action="" class="newsletter-form">
                                <input type="email" name="email" required placeholder="Email Address" class="newsletter-input">

                                <button type="submit" class="subscribe-btn btn-primary">Subscribe</button>
                            </form>

                        </div>

                    </div>

                </div>
            </div>

            <div class="footer-bottom">
                <div class="container">

                    <p class="copyright">
                    &copy; <a href="#" class="copyright-link">Nexus</a> Philippines. All Rights Reserved
                    </p>

                </div>

            </div>

        </footer>


        <!-- Go to top -->

        <a href="#top" class="go-top-btn" data-go-top>
            <ion-icon name="arrow-up-outline"></ion-icon>
        </a>


        <!-- Custome js -->
        <script src="./assets/js/script.js"></script>

        <!-- Ionicon link -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <!-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                const buttons = document.querySelectorAll('.variant-button');
                buttons.forEach(button => {
                    button.addEventListener('click', () => {
                        const variant = button.getAttribute('data-variant');
                        const form = button.closest('form');
                        const variantInput = form.querySelector('input[name="product_variant"]');
                        const selectedVariant = form.querySelector('.selected-variant');

                        variantInput.value = variant;
                        selectedVariant.textContent = variant;
                    });
                });
            });
        </script> -->
    </body>
</html>
