<?php

   @include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id'];

   if (!isset($admin_id)) {
      header('location:login.php');
   };

   if (isset($_POST['add-product'])) {
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $price = mysqli_real_escape_string($conn, $_POST['price']);
      $details = mysqli_real_escape_string($conn, $_POST['details']);
      $image = $_FILES['image']['name'];
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folter = 'assets/img/uploaded-img/'.$image;

      $select_product_name = mysqli_query($conn, "SELECT name FROM `product` WHERE name = '$name'") or die('query failed');

      if (mysqli_num_rows($select_product_name) > 0) {
         $_SESSION['messages'][] = 'Product name already exist!';
      } else {
         $insert_product = mysqli_query($conn, "INSERT INTO `product`(name, details, price, image) VALUES('$name', '$details', '$price', '$image')") or die('query failed');

         if ($insert_product) {
            // if ($image_size > 2000000) {
            //    $_SESSION['messages'][] = 'Image size is too large';
            // } else {
               move_uploaded_file($image_tmp_name, $image_folter);
               $_SESSION['messages'][] = 'Product added successfully!';
            // }
         }
      }

   }

   if (isset($_GET['delete'])) {
      $delete_id = $_GET['delete'];
      $select_delete_image = mysqli_query($conn, "SELECT image FROM `product` WHERE id = '$delete_id'") or die('query failed');
      $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
      unlink('assets/img/uploaded-img/'.$fetch_delete_image['image']);
      mysqli_query($conn, "DELETE FROM `product` WHERE id = '$delete_id'") or die('query failed');
      header('location:admin-page.php#products');

   }

   if (isset($_POST['update_order'])) {
      $order_id = $_POST['order_id'];
      $update_payment = $_POST['update_payment'];
      mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
      $_SESSION['messages'][] = 'Payment status has been updated!';
   }

   if (isset($_GET['delete'])) {
      $delete_id = $_GET['delete'];
      mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
      header('location:admin-page.php#orders');
   }

   if (isset($_GET['delete'])) {
      $delete_id = $_GET['delete'];
      mysqli_query($conn, "DELETE FROM `account` WHERE account_id = '$delete_id'") or die('query failed');
      header('location:admin-page.php#accounts');
   }

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin | Nexus</title>

      <link rel="icon" type="image/png" href="assets/img/bscs.png"/>
      <link rel="stylesheet" href="main.css">

   </head>
   <body>
      
      <?php include 'admin-nav.php'; ?>

      <div class="admin-container">
         <section id="dashboard" class="dashboard">
            <video controls autoplay muted loop>
               <source src="assets/vid/advertisement.mp4" type="video/mp4">
               Your browser does not support the video tag.
            </video>
            <h2 class="logo-name"><span style="color: #dd3157;">Nexus</span> Philippines</h2>
            <div class="box-container">
               <div class="box">
                  <?php 
                     $total_pendings = 0;
                     $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
                     while ($fetch_pendings = mysqli_fetch_assoc($select_pendings)) {
                        $total_pendings += $fetch_pendings['total_price'];
                     };
                  ?>
                  <h3>P<?php echo $total_pendings; ?></h3>
                  <p>Pendings orders amount</p>
               </div>

               <div class="box">
                  <?php
                     $total_completes = 0;
                     $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
                     while($fetch_completes = mysqli_fetch_assoc($select_completes)){
                        $total_completes += $fetch_completes['total_price'];
                     };
                  ?>
                  <h3>P<?php echo $total_completes; ?></h3>
                  <p>Total completed paymets</p>
               </div>

               <div class="box">
                  <?php
                     $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                     $number_of_orders = mysqli_num_rows($select_orders);
                  ?>
                  <h3><?php echo $number_of_orders; ?></h3>
                  <p>Number of orders placed</p>
               </div>

               <div class="box">
                  <?php
                     $select_products = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
                     $number_of_products = mysqli_num_rows($select_products);
                  ?>
                  <h3><?php echo $number_of_products; ?></h3>
                  <p>Number of products</p>
               </div>

               <div class="box">
                  <?php
                     $select_users = mysqli_query($conn, "SELECT * FROM `account` WHERE account_type = 'user'") or die('query failed');
                     $number_of_users = mysqli_num_rows($select_users);
                  ?>
                  <h3><?php echo $number_of_users; ?></h3>
                  <p>Number of customers</p>
               </div>

               <div class="box">
                  <?php
                     $select_admin = mysqli_query($conn, "SELECT * FROM `account` WHERE account_type = 'admin'") or die('query failed');
                     $number_of_admin = mysqli_num_rows($select_admin);
                  ?>
                  <h3><?php echo $number_of_admin; ?></h3>
                  <p>Number of Admins</p>
               </div>

            </div>

         </section>

         <section id="products" class="add-products">

            <img src="assets/img/phones/phone15.jpg" alt="Phone" class="display">
            <form action="" method="POST" enctype="multipart/form-data">
               <h3>Add new product</h3>
               <input type="text" class="box" required placeholder="Enter product name" name="name">
               <input type="number" min="0" class="box" required placeholder="Enter product price" name="price">
               <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"></textarea>
               <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
               <input type="submit" value="add product" name="add-product" class="btn">
            </form>

         </section>

         <section id="show-products" class="show-products">

            <div class="box-container">

               <?php
                  $select_products = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
                  if (mysqli_num_rows($select_products) > 0) {
                     while ($fetch_products = mysqli_fetch_assoc($select_products)) {
               ?>
                        <li class="product-item">
    <div class="product-card" tabindex="0">

        <figure class="card-banner">
            <img src="assets/img/uploaded-img/<?php echo $fetch_products['image']; ?>" width="312" height="350" loading="lazy" alt="Product Image" class="image-contain">
        </figure>

        <div class="card-content">
            <h3 class="h3 card-title">
                <a href="#"><?php echo $fetch_products['name']; ?></a>
            </h3>

            <p class="card-price">P<?php echo $fetch_products['price']; ?></p>
            
            <div class="card-cat">
                <a href="#" class="card-cat-link"><?php echo $fetch_products['details']; ?></a>
            </div>
        </div>

        <div class="admin-actions">
            <a href="admin-update-product.php?update=<?php echo $fetch_products['id']; ?>" class="btn">Update</a>
            <a href="admin-page.php?delete=<?php echo $fetch_products['id']; ?>" class="btn" onclick="return confirm('Delete this product?');">Delete</a>
        </div>

    </div>
</li>

                        <?php
                     }
                  } else {
                     echo '<p class="empty">No products added yet!</p>';
                  }
                        ?>
            </div>
            

         </section>

         <section id="orders" class="placed-orders">

            <h3 class="title">placed orders</h3>

            <div class="box-container">
               <?php   
               $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
               if (mysqli_num_rows($select_orders) > 0) {
                  while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
               ?>
                  <div class="box">
                     <p> User id : <span><?php echo $fetch_orders['account_id']; ?></span> </p>
                     <p> Placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                     <p> Name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                     <p> Number : <span><?php echo $fetch_orders['number']; ?></span> </p>
                     <p> Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                     <p> Address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                     <p> Products : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                     <p> Total price : <span>P<?php echo $fetch_orders['total_price']; ?></span> </p>
                     <p> Payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>
                     <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                        <select name="update_payment">
                           <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
                           <option value="pending">pending</option>
                           <option value="completed">completed</option>
                        </select>
                        <input type="submit" name="update_order" value="update" class="btn">
                        <a href="admin-page.php?delete=<?php echo $fetch_orders['id']; ?>" class="btn" onclick="return confirm('delete this order?');">delete</a>
                     </form>
                  </div>
               <?php
                  }
               } else {
                  echo '<p class="empty">no orders placed yet!</p>';
               }
               ?>
            </div>

         </section>

         <section id="accounts" class="users">

            <h3 class="title">users account</h3>

            <div class="box-container">
               <?php
               $select_users = mysqli_query($conn, "SELECT * FROM `account`") or die('query failed');
               if (mysqli_num_rows($select_users) > 0) {
                  while ($fetch_users = mysqli_fetch_assoc($select_users)) {
               ?>
                  <div class="box">
                     <p>user id : <span><?php echo $fetch_users['account_id']; ?></span></p>
                     <p>username : <span><?php echo $fetch_users['name']; ?></span></p>
                     <p>email : <span><?php echo $fetch_users['email']; ?></span></p>
                     <p>user type : <span style="color:<?php if($fetch_users['account_type'] == 'admin'){ echo 'var(--orange)'; }; ?>"><?php echo $fetch_users['account_type']; ?></span></p>
                     <a href="admin-page.php?delete=<?php echo $fetch_users['account_id']; ?>" onclick="return confirm('delete this account?');" class="btn">delete</a>
                  </div>
               <?php
                  }
               }
               ?>
            </div>

         </section>
      </div>

      <script src="js/admin-script.js"></script>

   </body>
</html>