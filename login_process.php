<?php
include 'db_connect.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
			$_SESSION['email'] = $user['email'];
			
			$role = strtolower($user['role']);

            // Redirect based on role
            if($user['role'] === 'Customer'){
                header("Location: customer_dashboard.php");
            } else {
                header("Location: admin_dashboard.php");
            }
            exit();
        } else {
            echo "Incorrect password. <a href='login.html'>Go back</a>";
        }
    } else {
        echo "Username not found. <a href='login.html'>Go back</a>";
    }

    $stmt->close();
}

$conn->close();
?>
