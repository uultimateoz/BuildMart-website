<?php
@include '../includes/config.php';
session_start();
$user_id = $_SESSION['user_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BuildMart - FAQ</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div style="max-width:800px; margin:20px auto; padding:20px;">
   <h2>Frequently Asked Questions</h2>
   <div style="background:white; padding:15px; margin:10px 0; border-radius:8px;"><h3>1. What products do you sell?</h3><p>We sell construction materials including cement, bricks, steel, wood, tools, safety equipment, and more.</p></div>
   <div style="background:white; padding:15px; margin:10px 0; border-radius:8px;"><h3>2. Do you offer delivery?</h3><p>Yes, we offer delivery for all orders above $50. Delivery typically takes 2-3 business days.</p></div>
   <div style="background:white; padding:15px; margin:10px 0; border-radius:8px;"><h3>3. Can I return products?</h3><p>Returns are accepted within 14 days of purchase. Products must be unused and in original packaging.</p></div>
   <div style="background:white; padding:15px; margin:10px 0; border-radius:8px;"><h3>4. How does the furniture design feature work?</h3><p>You can submit custom furniture designs with dimensions and material preferences. We'll provide a quote within 48 hours.</p></div>
   <div style="background:white; padding:15px; margin:10px 0; border-radius:8px;"><h3>5. Do you offer bulk discounts?</h3><p>Yes, contact our sales team for bulk orders over $1000 for special pricing.</p></div>
   <div style="background:white; padding:15px; margin:10px 0; border-radius:8px;"><h3>6. How do I track my order?</h3><p>Login to your account and go to 'orders' page to track your order status.</p></div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>