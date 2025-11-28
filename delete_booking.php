<?php
session_start();
include 'db_connect.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $conn->query("DELETE FROM bookings WHERE booking_id='$id'");
    $_SESSION['message'] = "Booking deleted successfully!";
}

header("Location: admin_dashboard.php?section=bookings");
exit();
?>
