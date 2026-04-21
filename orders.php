<?php
@include '../includes/config.php';
session_start();

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if($user_id == ''){
   header('location:../auth/login.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BuildMart - My Orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="orders">
   <h1 class="title">My Orders</h1>
   <div class="box-container">
   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY created_at DESC");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <p>Order ID: <span>#<?= $fetch_orders['id']; ?></span></p>
      <p>Total Amount: <span>$<?= number_format($fetch_orders['total_amount'], 2); ?></span></p>
      <p>Status: <span style="color:<?php 
         if($fetch_orders['status'] == 'pending') echo 'orange';
         elseif($fetch_orders['status'] == 'delivered') echo 'green';
         else echo 'blue';
      ?>"><?= $fetch_orders['status']; ?></span></p>
      <p>Date: <span><?= date('F d, Y', strtotime($fetch_orders['created_at'])); ?></span></p>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No orders yet! <a href="../pages/shop.php">Start Shopping</a></p>';
      }
   ?>
   </div>
</section>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>