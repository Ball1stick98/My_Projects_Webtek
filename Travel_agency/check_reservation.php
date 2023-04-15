<?php
  include('dbconn.php');

  $package_name = $_POST['package_name'];
  $name = $_POST['Name'];
  $phone_number = $_POST['Number'];
  $guests = $_POST['amount'];
  $email = $_POST['email'];

  $stmt = $conn->prepare("SELECT package_id FROM travel_packages WHERE package_name = ?");
  $stmt->bind_param("s", $package_name);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $package_id = $row['package_id'];
  $stmt->close();

  $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $customer_id = $row['customer_id'];
  $stmt->close();

  $reservation_id = uniqid();

  $stmt = $conn->prepare("CALL make_reservation(?, ?, ?, ?, @success)");
  $stmt->bind_param("ssss", $customer_id, $package_id, $guests, $reservation_id);
  $stmt->execute();
  $stmt->close();

  $result = $conn->query("SELECT @success AS success")->fetch_assoc();

  if ($result['success'] == 1) {
    header("Location: payment_form.php?package_id=$package_id&name=$name&phone_number=$phone_number&guests=$guests&email=$email&reservation_id=$reservation_id");
    exit();
  } else {
    // redirect the user to the home page
    header("Location: index.html");
    exit();
  }
?>
