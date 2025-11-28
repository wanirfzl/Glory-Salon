<?php
session_start();
include 'db_connect.php';

// Pastikan user logged in & role Customer
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'customer') {
    header("Location: login.html");
    exit();
}

// Pastikan id dihantar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid booking ID.";
    header("Location: customer_dashboard.php");
    exit();
}

$booking_id = (int) $_GET['id'];
$user_email = $_SESSION['email'];   // or use $_SESSION['user_id'] if you have user_id column

// Semak booking milik customer (ikut email)
$stmt = $conn->prepare("SELECT booking_id FROM bookings WHERE booking_id = ? AND email = ?");
$stmt->bind_param("is", $booking_id, $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    // Pilihan A: DELETE (buang rekod)
    $del = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $del->bind_param("i", $booking_id);
    $ok = $del->execute();
    $del->close();

    // -- ATAU --
    // Pilihan B: tandakan status sebagai Cancelled (jika nak simpan rekod)
    // $upd = $conn->prepare("UPDATE bookings SET status = 'Cancelled' WHERE booking_id = ?");
    // $upd->bind_param("i", $booking_id);
    // $ok = $upd->execute();
    // $upd->close();

    if ($ok) {
        $_SESSION['message'] = "Booking cancelled successfully!";
    } else {
        $_SESSION['message'] = "Failed to cancel booking. Please try again.";
    }
} else {
    $_SESSION['message'] = "Unauthorized action or booking not found.";
}

$stmt->close();
header("Location: customer_dashboard.php");
exit();
?>
