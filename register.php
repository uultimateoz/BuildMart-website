<?php
@include '../includes/config.php';
session_start();

if(isset($_SESSION['user_id'])){
   header('location:/buildMart/pages/home.php');
   exit();
}

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = $_POST['cpass'];
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'user email already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert->execute([$name, $email, $hashed_pass]);
         $message[] = 'registered successfully!';
         header('location:/buildMart/auth/login.php');
         exit();
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BuildMart - Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="/buildMart/assets/css/style.css">
</head>
<body>

<?php include '/buildMart/includes/header.php'; ?>

<?php if(isset($message)) foreach($message as $msg) echo '<div class="message"><span>'.$msg.'</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>'; ?>

<section class="form-container">
   <form action="" method="POST">
      <h3>register now</h3>
      <input type="text" name="name" class="box" placeholder="enter your name" required>
      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="password" name="pass" class="box" placeholder="enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
      <input type="submit" value="register now" class="btn" name="submit">
      <p>already have an account? <a href="/buildMart/auth/login.php">login now</a></p>
   </form>
</section>

<?php include '/buildMart/includes/footer.php'; ?>
<script src="/buildMart/assets/js/script.js"></script>
</body>
</html>