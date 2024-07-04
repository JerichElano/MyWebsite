<?php

    @include 'config.php';

    session_start();
    
    // Initialize session messages array if not already initialized
    if (!isset($_SESSION['messages'])) {
        $_SESSION['messages'] = array();
    }
    if (isset($_GET['pid'])) {
        $product_id = intval($_GET['pid']);
    
        // Fetch product details from database
        $result = $conn->query("SELECT * FROM `product` WHERE `id` = $product_id");
    
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        } else {
            echo "<p>Product not found.</p>";
        }
    } else {
        echo "<p>No product ID provided.</p>";
    }
    //
    
    if (isset($_SESSION['account_id'])) {

        $account_id = $_SESSION['account_id'];
    
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
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Detail | Nexus</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
    <!-- CSS -->
    <link href="./assets/css/productDetail.css" rel="stylesheet">
    <meta name="robots" content="noindex,follow" />

    <link rel="icon" type="image/png" href="./assets/img/bscs.png"/>
    <link rel="stylesheet" type="text/css" href="main.css">

  </head>

  <body>
  <?php include ('nav.php'); ?>
    <h2 class="h2 section-title">Product Detail</h2>
    <main class="productDetailContainer">


      <!-- Left Column / Headphones Image -->
      <div class="left-column">
        <img data-image="black" src="./assets/img/uploaded-img/<?php echo $product['image']?>" alt="">
      </div>


      <!-- Right Column -->
      <div class="right-column">

        <!-- Product Description -->
        <div class="product-description">
          <span><?php echo $product['category']; ?></span>
          <h1><?php echo $product['name']; ?></h1>
          <p><?php echo $product['details']; ?></p>
        </div>

        <!-- Product Configuration -->
        <!-- <div class="product-configuration"> -->

          <!-- Product Color -->
          <!-- <div class="product-color">
            <span>Color</span>

            <div class="color-choose">
              <div>
                <input data-image="red" type="radio" id="red" name="color" value="red" checked>
                <label for="red"><span></span></label>
              </div>
              <div>
                <input data-image="blue" type="radio" id="blue" name="color" value="blue">
                <label for="blue"><span></span></label>
              </div>
              <div>
                <input data-image="black" type="radio" id="black" name="color" value="black">
                <label for="black"><span></span></label>
              </div>
            </div>

          </div> -->

          <!-- Cable Configuration -->
          <!-- <div class="cable-config">
            <span>Cable configuration</span>

            <div class="cable-choose">
              <button>Straight</button>
              <button>Coiled</button>
              <button>Long-coiled</button>
            </div>

            <a href="#">How to configurate your headphones</a>
          </div>
        </div> -->

        <!-- Product Pricing -->
        <form action="" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
            <input type="hidden" name="product_quantity" value="1">

            <div class="product-price">
                <span>P <?php echo $product['price']; ?></span>
                <button type="submit" name="add_to_cart" class="btn">
                    Add to Cart
                </button>
            </div>
        </form>
      </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script>
    <script src="script.js" charset="utf-8"></script>
  </body>
</html>
