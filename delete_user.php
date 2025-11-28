<?php
session_start();
include 'db_connect.php';

// Ambil user_id dari URL
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $conn->query("DELETE FROM users WHERE user_id='$id'");
	$_SESSION['message'] = "User deleted successfully!";
}

header("Location: admin_dashboard.php?section=users");
exit();
?>
