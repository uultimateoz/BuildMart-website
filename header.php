<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <div class="flex">

      <a href="../pages/home.php" class="logo"><i class="fas fa-hard-hat"></i> BuildMart</a>

      <nav class="navbar">
       <a href="/buildMart/pages/home.php">HOME</a>
       <a href="/buildMart/pages/shop.php">SHOP</a>
       <a href="/buildMart/cart/orders.php">ORDERS</a>
       <a href="/buildMart/pages/about.php">ABOUT</a>
       <a href="/buildMart/pages/contact.php">CONTACT</a>
       <a href="/buildMart/pages/faq.php">FAQ</a>
       <a href="/buildMart/pages/furniture-designer.php">DESIGN</a>
       <?php if(isset($_SESSION['user_id'])): ?>
       <?php 
         // Check if user is admin (user_id = 1 or user_type = 'admin')
         $check_admin = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND (user_type = 'admin' OR id = 1)");
         $check_admin->execute([$_SESSION['user_id']]);
         if($check_admin->rowCount() > 0):
       ?>
         <a href="/buildMart/admin/dashboard.php">ADMIN</a>
       <?php endif; ?>
       <?php endif; ?>
     </nav>

   <div class="icons">
       <div id="menu-btn" class="fas fa-bars"></div>
       <div id="user-btn" class="fas fa-user"></div>
       <a href="/buildMart/pages/search_page.php" class="fas fa-search"></a>
       <?php
         if(isset($_SESSION['user_id'])) {
           $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
           $count_cart_items->execute([$_SESSION['user_id']]);
           $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
           $count_wishlist_items->execute([$_SESSION['user_id']]);
           $cart_count = $count_cart_items->rowCount();
           $wishlist_count = $count_wishlist_items->rowCount();
         } else {
           $cart_count = 0;
           $wishlist_count = 0;
         }
       ?>
       <a href="/buildMart/pages/wishlist.php"><i class="fas fa-heart"></i><span>(<?= $wishlist_count; ?>)</span></a>
       <a href="/buildMart/cart/cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $cart_count; ?>)</span></a>
   </div>

      <div class="profile">
         <?php if(isset($_SESSION['user_id'])): ?>
            <?php
               $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_profile->execute([$_SESSION['user_id']]);
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="/buildMart/assets/images/<?= $fetch_profile['image']; ?>" alt="">
            <p><?= $fetch_profile['name']; ?></p>
            <a href="/buildMart/pages/user_profile_update.php" class="btn">update profile</a>
            <a href="/buildMart/auth/logout.php" class="delete-btn">logout</a>
         <?php else: ?>
            <div class="flex-btn">
               <a href="/buildMart/auth/login.php" class="option-btn">login</a>
               <a href="/buildMart/auth/register.php" class="option-btn">register</a>
            </div>
         <?php endif; ?>
      </div>
   </div>
</header>