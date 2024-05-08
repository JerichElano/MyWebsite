<?php

@include 'config.php';

session_start();

$account_id = $_SESSION['account_id'];

if(!isset($account_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
    header('location:cart.php');
};
// fix the bug here -------------------------------------(it changes every quantity of the products that are in the same cart)
if(isset($_POST['update_quantity'])){
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'cart quantity updated!';
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

    <style>
    .heading,
    .shopping-cart{
    text-align: center;
    }

    .heading {
        margin-top: 80px;
        margin-bottom: 20px;
        font-size: 30px;
    }

    .title {
        margin: 20px 0;
    }

    .shopping-cart .box-container{
        max-width: 1200px;
        background-color: var(--white);
        margin: auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, 33rem);
        gap:1.5rem;
        align-items: flex-start;
        justify-content: center;
    }

    .shopping-cart .box-container .box{
        padding:2rem;
        text-align: center;
        background-color: lavenderblush;
        border:var(--border);
        box-shadow: var(--box-shadow);
        border-radius: .5rem;
        position: relative;
    }

    .shopping-cart .box-container .box .image{
        height: 200px;
        width: 50%;
        object-fit: cover;
    }

    .shopping-cart .box-container .box .fa-eye,
    .shopping-cart .box-container .box .fa-times{
        position: absolute;
        top:1rem;
        height: 4.5rem;
        width: 4.5rem;
        line-height: 4.3rem;
        font-size: 2rem;
        border-radius: .5rem;
    }

.shopping-cart .box-container .box .fa-eye{
   right:1rem;
   border:var(--border);
   background-color: var(--white);
   color:var(--black);
}

.shopping-cart .box-container .box .fa-eye:hover{
   background-color: var(--black);
   color:var(--white);
}

.shopping-cart .box-container .box .fa-times{
   background-color: var(--red);
   color:var(--white);
}

.shopping-cart .box-container .box .fa-times:hover{
   background-color: var(--black);
}

.shopping-cart .box-container .box .name{
   font-size: 1.5rem;
   color:var(--black);
   margin:1rem 0;
}

.shopping-cart .box-container .box .price{
   font-size: 2rem;
   color:var(--red);
   margin:.5rem 0;
}

.shopping-cart .box-container .box .qty{
   width: 9rem;
   padding: .4rem 1.2rem;
   border-radius: .5rem;
   border:var(--border);
   font-size: 1.3rem;
}

.shopping-cart .box-container .box .sub-total{
   margin-top: 1.5rem;
   font-size: 1.8rem;
   color:var(--light-color);
}

.shopping-cart .box-container .box .sub-total span{
   color:var(--red);
}

.shopping-cart .cart-total{
   max-width: 1200px;
   margin:0 auto;
   padding:2rem;
   text-align: center;
   background-color: var(--white);
   border:var(--border);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   margin-top: 2rem;
}

.shopping-cart .cart-total p{
   font-size: 2rem;
   color:var(--light-color);
   margin-bottom: 1rem;
}

.shopping-cart .cart-total p span{
   color:var(--red);
}
    </style>
</head>
<body>
   
<?php @include 'nav.php'; ?>

<section class="heading">
    <h3>shopping cart</h3>
</section>

<section class="shopping-cart">

    <h1 class="title">products added</h1>

    <div class="box-container">

        <?php
            $grand_total = 0;
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE account_id = '$account_id'") or die('query failed');
            if(mysqli_num_rows($select_cart) > 0){
                while($fetch_cart = mysqli_fetch_assoc($select_cart)){
        ?>
        <div  class="box">
            <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
            <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
            <img src="assets/img/uploaded-img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
            <div class="name"><?php echo $fetch_cart['name']; ?></div>
            <div class="price">P<?php echo $fetch_cart['price']; ?></div>
            <form action="" method="post">
                <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
                <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>" name="cart_quantity" class="qty">
                <input type="submit" value="update" class="option-btn" name="update_quantity">
            </form>
            <div class="sub-total"> Sub-total : <span>P<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></span> </div>
        </div>
        <?php
        $grand_total += $sub_total;
            }
        }else{
            echo '<p class="empty">your cart is empty</p>';
        }
        ?>
    </div>

    <div class="more-btn">
        <a href="cart.php?delete_all" class="btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('delete all from cart?');">delete all</a>
    </div>

    <div class="cart-total">
        <p>Total Price : <span>P<?php echo $grand_total; ?></span></p>
        <a href="index.php" class="btn">Continue Shopping</a>
        <a href="checkout.php" class="btn  <?php echo ($grand_total > 1)?'':'disabled' ?>">Checkout</a>
    </div>

</section>






<script src="assets/js/script.js"></script>

</body>
</html>