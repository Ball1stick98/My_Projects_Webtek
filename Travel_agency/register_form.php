<?php
include('dbconn.php');

if(isset($_POST['submit'])) {
  $name = mysqli_real_escape_string($conn, $_POST["name"]);
  $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $password = mysqli_real_escape_string($conn, $_POST["password"]);
  $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);

  $query = "SELECT * FROM customers WHERE email='$email'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) > 0) {
    echo "Error: Email already in use";
  } else {

    $query = "INSERT INTO customers (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
    $result = mysqli_query($conn, $query);

    if($result) {
      echo "User registered successfully";
      header('Location: login_form.php');
      exit();
    } else {
      echo "Error: " . mysqli_error($conn);
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
   <title>register form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/login.css">

</head>
<body>

<div class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="name" required placeholder="enter your name">
      <input type="email" name="email" required placeholder="enter your email">
      <input type="number" name="phone" required placeholder="enter your number">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="password" name="cpassword" required placeholder="confirm your password">
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>already have an account? <a href="login_form.php">login now</a></p>
   </form>

</div>

</body>
</html>
