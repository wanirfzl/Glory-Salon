<?php
session_start();
include 'db_connect.php';

// Redirect kalau bukan customer
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Customer') {
    header("Location: login.html");
    exit();
}

// Dapatkan booking ID
if (!isset($_GET['id'])) {
    die("Invalid booking ID.");
}
$booking_id = $_GET['id'];

// Dapatkan data booking
$stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found.");
}

// Update booking apabila form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $update = $conn->prepare("UPDATE bookings SET service=?, date=?, time=? WHERE booking_id=?");
    $update->bind_param("sssi", $service, $date, $time, $booking_id);

    if ($update->execute()) {
        $_SESSION['message'] = "Booking updated successfully!";
        header("Location: customer_dashboard.php");
        exit();
    } else {
        $error = "Failed to update booking.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Booking | Glory Salon</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f7f7f7;
    margin: 0;
    display: flex;
    justify-content: center;
    padding-top: 40px;
}

/* White card layout sama macam admin */
.container {
    width: 420px;
    background: #fff;
    padding: 28px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

h2 {
    color: #f06292;
    text-align: center;
    margin-bottom: 20px;
}

/* Form styling sama admin */
label {
    font-weight: 600;
    margin-top: 12px;
    display: block;
}

input, select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-top: 5px;
    font-size: 15px;
}

/* Button styling */
button {
    width: 100%;
    padding: 12px;
    background: #f06292;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    margin-top: 20px;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    background: #e05282;
}

.cancel-btn {
    background: #9e9e9e;
}
.cancel-btn:hover {
    background: #7d7d7d;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="container">

    <h2>Edit Booking</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">

        <label>Service</label>
        <select name="service" required>
            <option value="">-- Select Service --</option>
            <option value="Haircut"        <?= $booking['service']=="Haircut" ? "selected" : "" ?>>Haircut</option>
            <option value="Hair Coloring"  <?= $booking['service']=="Hair Coloring" ? "selected" : "" ?>>Hair Coloring</option>
            <option value="Hair Treatment" <?= $booking['service']=="Hair Treatment" ? "selected" : "" ?>>Hair Treatment</option>
            <option value="Makeup"         <?= $booking['service']=="Makeup" ? "selected" : "" ?>>Makeup</option>
            <option value="Manicure"       <?= $booking['service']=="Manicure" ? "selected" : "" ?>>Manicure</option>
            <option value="Pedicure"       <?= $booking['service']=="Pedicure" ? "selected" : "" ?>>Pedicure</option>
        </select>

        <label>Date</label>
        <input type="date" name="date" value="<?= $booking['date'] ?>" required>

        <label>Time</label>
        <input type="time" name="time" value="<?= $booking['time'] ?>" required>

        <button type="submit">Update Booking</button>
        <button type="button" class="cancel-btn" onclick="location.href='customer_dashboard.php'">Cancel</button>

    </form>
</div>

</body>
</html>
