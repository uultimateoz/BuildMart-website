<?php
@include '../includes/config.php';
session_start();
$user_id = $_SESSION['user_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BuildMart - About Us</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="about">
   <h1 class="title">about us</h1>
   <div class="content">
      <h3>why choose us?</h3>
      <p>We provide high-quality construction materials and custom furniture design services.</p>
   </div>
</section>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>