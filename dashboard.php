<?php
session_start();
include '../config.php';

// Check if user is admin
if(!isset($_SESSION['user_id'])) {
   header('location:../login.php');
   exit();
}

// Check admin status (you may need to add 'user_type' column to users table)
// For now, let's assume user with id=1 is admin
if($_SESSION['user_id'] != 1) {
   header('location:../home.php');
   exit();
}

// Handle Add Product
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $details = $_POST['details'];
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/'.$image;
    
    $insert_query = "INSERT INTO products (name, price, image, details) VALUES (:name, :price, :image, :details)";
    $stmt = $conn->prepare($insert_query);
    $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':image' => $image,
        ':details' => $details
    ]);
    move_uploaded_file($image_tmp_name, $image_folder);
    $message[] = 'Product added successfully!';
}

// Handle Delete Product
if(isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_query = "DELETE FROM products WHERE id = :id";
    $stmt = $conn->prepare($delete_query);
    $stmt->execute([':id' => $delete_id]);
    header('location:dashboard.php');
    exit();
}

// Handle Update Product
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $update_id = $_POST['update_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];
    $update_details = $_POST['update_details'];
    
    $update_query = "UPDATE products SET name = :name, price = :price, details = :details WHERE id = :id";
    $stmt = $conn->prepare($update_query);
    $stmt->execute([
        ':name' => $update_name,
        ':price' => $update_price,
        ':details' => $update_details,
        ':id' => $update_id
    ]);
    
    $message[] = 'Product updated successfully!';
    header('location:dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="heading">
   <h3>Admin Dashboard</h3>
   <p><a href="../home.php">home</a> / Admin</p>
</div>

<section class="add-products">
   <h1 class="title">Add New Product</h1>
   <form action="" method="POST" enctype="multipart/form-data">
      <div class="box">
         <input type="text" name="name" class="box" placeholder="Enter product name" required>
      </div>
      <div class="box">
         <input type="number" name="price" class="box" placeholder="Enter product price" required>
      </div>
      <div class="box">
         <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      </div>
      <div class="box">
         <textarea name="details" class="box" placeholder="Enter product details" rows="5" required></textarea>
      </div>
      <input type="submit" value="Add Product" name="add_product" class="btn">
   </form>
</section>

<section class="show-products">
   <h1 class="title">Products List</h1>
   <div class="box-container">
      <?php
      $select_products = $conn->prepare("SELECT * FROM products");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <img src="../uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
         <div class="details"><?php echo $fetch_products['details']; ?></div>
         <div class="flex-btn">
            <a href="dashboard.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
            <a href="dashboard.php?edit=<?php echo $fetch_products['id']; ?>" class="option-btn">Update</a>
         </div>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>
   </div>
</section>

<!-- Update Product Modal -->
<?php if(isset($_GET['edit'])): ?>
   <?php 
   $edit_id = $_GET['edit'];
   $edit_query = $conn->prepare("SELECT * FROM products WHERE id = :id");
   $edit_query->execute([':id' => $edit_id]);
   $fetch_edit = $edit_query->fetch(PDO::FETCH_ASSOC);
   ?>
   <div class="update-form">
      <form action="" method="POST">
         <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?>">
         <input type="text" name="update_name" value="<?php echo $fetch_edit['name']; ?>" class="box" required>
         <input type="number" name="update_price" value="<?php echo $fetch_edit['price']; ?>" class="box" required>
         <textarea name="update_details" class="box" rows="5" required><?php echo $fetch_edit['details']; ?></textarea>
         <input type="submit" value="Update Product" name="update_product" class="btn">
         <a href="dashboard.php" class="delete-btn">Cancel</a>
      </form>
   </div>
<?php endif; ?>

<style>
.update-form {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    z-index: 1000;
}
</style>

<?php include '../footer.php'; ?>
</body>
</html>