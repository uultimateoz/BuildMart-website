<?php
@include '../includes/config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:../auth/login.php');
   exit();
}

// Get product details
if(isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $select_product->execute([$pid]);
    $product = $select_product->fetch(PDO::FETCH_ASSOC);
}

// Add to cart
if(isset($_POST['add_to_cart'])) {
    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];
    $p_qty = $_POST['p_qty'];

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart->execute([$p_name, $user_id]);

    if($check_cart->rowCount() > 0) {
        $message[] = 'already added to cart!';
    } else {
        $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
        $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
        $message[] = 'added to cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BuildMart - Product Details</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="product-details">
   <h1 class="title">Product Details</h1>
   
   <?php if($product && $select_product->rowCount() > 0): ?>
   <div class="box-container">
      <div class="box">
         <img src="../assets/images/<?= $product['image']; ?>" alt="">
         <div class="name"><?= $product['name']; ?></div>
         <div class="price">$<?= $product['price']; ?>/-</div>
         <div class="details"><?= $product['details']; ?></div>
         <form action="" method="POST">
            <input type="hidden" name="pid" value="<?= $product['id']; ?>">
            <input type="hidden" name="p_name" value="<?= $product['name']; ?>">
            <input type="hidden" name="p_price" value="<?= $product['price']; ?>">
            <input type="hidden" name="p_image" value="<?= $product['image']; ?>">
            <input type="number" name="p_qty" value="1" min="1" class="qty">
            <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
         </form>
         <a href="shop.php" class="option-btn">Continue Shopping</a>
      </div>
   </div>
   <?php else: ?>
      <p class="empty">Product not found!</p>
   <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>

<script src="../assets/js/script.js"></script>

</body>
</html>