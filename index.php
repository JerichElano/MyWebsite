<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>Nexus</title>

        <!-- Logo -->
	    <link rel="icon" type="image/png" href="assets/img/bscs.png"/>

        <!-- CSS File -->
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
                    <h2 class="">Welcome to Nexus</h2>
                    <p>Your Portal to Infinite Connectivity</p>
                    <a href="#brands" class="btn-learn-more">Learn more</a>
                </div>
            </div>
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
            <div class="brands">
                <h2 id="brands">Smartphones</h2>
                <div class="brands-container">
                    <div class="row">
                        <div class="column">
                            <img src="assets/img/phones/phone1.jpg">
                            <img src="assets/img/phones/phone2.jpg">
                            <img src="assets/img/phones/phone3.jpg">
                        </div>
                        <div class="column">
                            <img src="assets/img/phones/phone8.jpg">
                            <img src="assets/img/phones/phone9.jpg">
                            <img src="assets/img/phones/phone11.jpg">
                            <img src="assets/img/phones/phone4.jpg">
                        </div>
                        <div class="column">
                            <img src="assets/img/phones/phone4.jpg">
                            <img src="assets/img/phones/phone7.jpg">
                            <img src="assets/img/phones/phone5.jpg">
                            <img src="assets/img/phones/phone15.jpg">
                        </div>
                        <div class="column">
                            <img src="assets/img/phones/phone16.jpg">
                            <img src="assets/img/phones/phone14.jpg">
                            <img src="assets/img/phones/phone12.jpg">
                            <img src="assets/img/phones/phone6.jpg">
                        </div>
                    </div>
                </div>
            </div>
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
            <div class="recommended">
                <h2 id="recommended">Recommended Products</h2>
                <div class="box-container">
                    <?php
                        $select_products = mysqli_query($conn, "SELECT * FROM `product` LIMIT 8") or die('query failed');
                        if(mysqli_num_rows($select_products) > 0){
                            while($fetch_products = mysqli_fetch_assoc($select_products)){
                    ?>
                    <form action="" method="POST" class="box">
                        <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
                        <div class="price">$<?php echo $fetch_products['price']; ?></div>
                        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                        <input type="number" name="product_quantity" value="1" min="0" class="qty">
                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                        <input type="submit" value="buy now" name="buy_now" class="buy-now-btn">
                        <input type="submit" value="add to cart" name="add_to_cart" class="add-to-cart-btn">
                    </form>
                    <?php
                        }
                    }else{
                        echo '<p class="empty">no products added yet!</p>';
                    }
                    ?>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="top-footer">
                    <div class="nexus-phil"><h3>Nexus Philippines</h3></div>
                </div>
                <br>
                <div class="bottom-footer">
                    <div class="links">
                        <a href="https://www.facebook.com/">
                            <img src="assets/img/facebook.png" alt="#">
                        </a>
                        <a href="https://www.tiktok.com/">
                            <img src="assets/img/tiktok.png" alt="#">
                        </a>
                        <a href="https://www.instagram.com/">
                            <img src="assets/img/instagram.png" alt="#">
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
