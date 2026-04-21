<?php
@include '../includes/config.php';
session_start();

if(isset($_SESSION['user_id'])){
   header('location:../pages/home.php');
   exit();
}
// ... rest of login code
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>BuildMart - Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/header.php'; ?>
<!-- rest of login form -->
<?php include '../includes/footer.php'; ?>
</body>
</html>