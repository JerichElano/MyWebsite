<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);

   mysqli_query($conn, "UPDATE `product` SET name = '$name', details = '$details', price = '$price' WHERE id = '$update_p_id'") or die('query failed');

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'assets/img/uploaded-img/'.$image;
   $old_image = $_POST['update_p_image'];
   
   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image file size is too large!';
      }else{
         mysqli_query($conn, "UPDATE `product` SET image = '$image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($image_tmp_name, $image_folter);
         unlink('assets/img/uploaded-img/'.$old_image);
         $_SESSION['messages'][] = 'Image updated successfully!';
      }
   }

   $_SESSION['messages'][] = 'Product updated successfully!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="main.css">

</head>
<body>
   
<?php @include 'admin-nav.php'; ?>

<section class="update-product">

<?php

   $update_id = $_GET['update'];
   $select_products = mysqli_query($conn, "SELECT * FROM `product` WHERE id = '$update_id'") or die('query failed');
   if(mysqli_num_rows($select_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($select_products)){
?>

<!-- <li class="product-item">
    <div class="product-card" tabindex="0">
        <form action="" method="post" enctype="multipart/form-data">
            <figure class="card-banner">
                <img src="assets/img/uploaded-img/<?php echo $fetch_products['image']; ?>" width="312" height="350" loading="lazy" alt="Product Image" class="image-contain">
            </figure>

            <div class="card-content">
                <h3 class="h3 card-title">
                    <input type="text" class="box" value="<?php echo $fetch_products['name']; ?>" required placeholder="update product name" name="name">
                </h3>

                <p class="card-price">
                    <input type="number" min="0" class="box" value="<?php echo $fetch_products['price']; ?>" required placeholder="update product price" name="price">
                </p>
                
                <div class="card-cat">
                    <textarea name="details" class="box" required placeholder="update product details" cols="30" rows="10"><?php echo $fetch_products['details']; ?></textarea>
                </div>
            </div>

            <div class="admin-actions">
                <input type="hidden" value="<?php echo $fetch_products['id']; ?>" name="update_p_id">
                <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_p_image">
                <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
                <input type="submit" value="update product" name="update_product" class="btn">
                <a href="admin-page.php#show-products" class="btn">go back</a>
            </div>
        </form>
    </div>
</li> -->


<form action="" method="post" enctype="multipart/form-data">
   <img src="assets/img/uploaded-img/<?php echo $fetch_products['image']; ?>" class="image"  alt="">
   <input type="hidden" value="<?php echo $fetch_products['id']; ?>" name="update_p_id">
   <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_p_image">
   <input type="text" class="box" value="<?php echo $fetch_products['name']; ?>" required placeholder="update product name" name="name">
   <input type="number" min="0" class="box" value="<?php echo $fetch_products['price']; ?>" required placeholder="update product price" name="price">
   <textarea name="details" class="box" required placeholder="update product details" cols="30" rows="10"><?php echo $fetch_products['details']; ?></textarea>
   <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
   <input type="submit" value="update product" name="update_product" class="btn">
   <a href="admin-page.php#show-products" class="btn">go back</a>
</form>

<?php
      }
   }else{
      echo '<p class="empty">no update product select</p>';
   }
?>
</section>













<script src="assets/js/admin-script.js"></script>

</body>
</html>