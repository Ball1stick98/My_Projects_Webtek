<?php
include('dbconn.php');

if (isset($_POST['submit'])) {
   $amount = "";
   $name = "";
   $cardNo = "";
   $expiryMonth = "";
   $expiryYear = "";
   $cvv = "";
   $cardType = "";
   $paymentAt = date("d-M-Y");
   $balance = "";

   if (isset($_POST['amount'])) {
      $amount = $_POST['amount'];
   }

   if (isset($_POST['name'])) {
      $name = $_POST['name'];
   }

   if (isset($_POST['cardNo'])) {
      $cardNo = $_POST['cardNo'];
   }

   if (isset($_POST['expiryMonth'])) {
      $expiryMonth = $_POST['expiryMonth'];
   }

   if (isset($_POST['expiryYear'])) {
      $expiryYear = $_POST['expiryYear'];
   }

   if (isset($_POST['cvv'])) {
      $cvv = $_POST['cvv'];
   }

   if (isset($_POST['cardType'])) {
      $cardType = $_POST['cardType'];
   }

   if ($amount == "") {
      echo "Amount is required";
   }

   if ($name == "") {
      echo "Name is required";
   }

   if ($cardNo == "") {
      echo "Card No. is required";
   }

   if ($expiryMonth == "") {
      echo "Expiry Month is required";
   }

   if ($expiryYear == "") {
      echo "Expiry Year is required";
   }

   if ($cvv == "") {
      echo "CVV is required";
   }


   $connection = getConnection();
   $sqlQuery = "SELECT * FROM card WHERE card_no = '$cardNo' AND expiry_month = '$expiryMonth' AND expiry_year = '$expiryYear' AND cvv = '$cvv';";

   $result = mysqli_query($connection, $sqlQuery);
   $cardInfo = mysqli_fetch_assoc($result);
   mysqli_close($connection);

   if ($cardInfo) {
      $cardId = $cardInfo['card_id'];
      $balance = $cardInfo['balance'];

      if ($balance < $amount) {
         echo "Insufficient fund";
      } else {
         $connection = getConnection();
         $newBalance = $balance - $amount;
         $sqlQuery = "UPDATE card SET Balance = $newBalance";
         $result = mysqli_query($connection, $sqlQuery);
         mysqli_close($connection);

         $connection = getConnection();
         $sqlQuery = "SELECT MAX(payment_id) AS payment_id FROM payments;";
         $result = mysqli_query($connection, $sqlQuery);
         $maxPaymentData = mysqli_fetch_assoc($result);
         mysqli_close($connection);
         $maxPaymentId = $maxPaymentData['payment_id'];

         if ($maxPaymentId) {
            $connection = getConnection();
            $sqlQuery = "INSERT INTO payments(payment_id, card_id, payment_date, amount) VALUES
                        ($maxPaymentId + 1, $cardId,'$paymentAt', $amount)";

            $status = mysqli_query($connection, $sqlQuery);
            mysqli_close($connection);
            echo "Payment successful";
         } else {
            echo "Could not get max payment ID";
         }
      }
   } else {
      echo "Invalid card info";
   }
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment Form</title>
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <div class="form-container">
      <form action="#" method="post">
         <h3>Pay Now</h3>
         <input type="number" name="amount" required placeholder="Enter payable amount">
         <input type="text" name="name" required placeholder="Enter your name">
         <input type="number" name="cardNo" required placeholder="Enter your card no.">
         <input type="number" name="expiryMonth" required placeholder="Enter your expiry month">
         <input type="number" name="expiryYear" required placeholder="Enter your expiry year">
         <input type="number" name="cvv" required placeholder="Enter your CVV">
         <select name="card_type">
            <option value="visa">Visa</option>
            <option value="masterCard">MasterCard</option>
            <option value="amex">American Express</option>
            <option value="unionPay">Union Pay</option>
            <option value="dci">Diners Club International</option>
         </select>
         <input type="submit" name="submit" value="Pay Now" class="form-btn">
      </form>
   </div>
</body>

</html>
