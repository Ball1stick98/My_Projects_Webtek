<?php
$showAlert = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'db.php';
    // Get form data
    $customer_id = $_POST['cid'];
    $sql = "SELECT total_cost FROM bills WHERE customer_id = $customer_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_cost = $row['total_cost'];

    //get input values from form
    $rid = $_POST['rid'];
    $cid = $_POST['cid'];
    $pid = $_POST['pid'];
    $dob = $_POST['dob'];
    $rd = $_POST['rd'];
    $st = $_POST['st'];
    $ng = $_POST['ng'];

    //calculate refund amount
    $refund_amount = 0;
    $reservation_date = new DateTime($dob);
    $today = new DateTime();
    $diff = $today->diff($reservation_date);
    $weeks_diff = floor($diff->days / 7);
    if ($weeks_diff >= 3) {
        $refund_amount = $total_cost;
    } elseif ($weeks_diff == 2) {
        $refund_amount = $total_cost * 0.75;
    } elseif ($weeks_diff == 1) {
        $refund_amount = $total_cost * 0.5;
    }

    //insert data into reservations table
    $sql = "INSERT INTO reservations (reservation_id, customer_id, package_id, reservation_date, applying_for_position, status, num_guests, refund_amount) 
    VALUES ('$rid', '$cid', '$pid', '$dob', '$rd', '$st', '$ng', '$refund_amount')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    
}
?>