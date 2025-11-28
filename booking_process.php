<?php
session_start();
include 'db_connect.php';

$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$service = $_POST['service'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$note = $_POST['note'] ?? NULL;

$stmt = $conn->prepare("INSERT INTO bookings (fullname, email, service, date, time, note) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $fullname, $email, $service, $date, $time, $note);

if($stmt->execute()){
    $_SESSION['booking'] = [
        'fullname' => $fullname,
        'email' => $email,
        'service' => $service,
        'date' => $date,
        'time' => $time,
        'note' => $note
    ];
    header('Location: booking_confirmation.php');
}else{
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
