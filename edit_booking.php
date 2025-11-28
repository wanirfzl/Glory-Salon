<?php
include 'db_connect.php';
$id = $_GET['id'];
$booking = $conn->query("SELECT * FROM bookings WHERE booking_id='$id'")->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $note = $_POST['note'];

    $conn->query("UPDATE bookings SET 
        fullname='$fullname', 
        email='$email', 
        service='$service',
        date='$date',
        time='$time',
        note='$note' 
    WHERE booking_id='$id'");

    session_start();
    $_SESSION['message'] = "Booking updated successfully!";
    header("Location: admin_dashboard.php?section=bookings");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Booking</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f7f7f7;
    padding:40px;
}
.card{
    max-width:600px;
    margin:auto;
    background:white;
    padding:25px 30px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
.card h2{
    color:#f06292;
    margin-bottom:20px;
}
.table-input{
    width:100%;
    border-collapse:collapse;
}
.table-input td{
    padding:10px 0;
}
.table-input td:first-child{
    font-weight:600;
    color:#555;
    width:150px;
}	
select, input{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
}
button{
    width:100%;
    padding:12px;
    background:#f06292;
    border:none;
    border-radius:6px;
    color:white;
    font-weight:600;
    cursor:pointer;
    margin-top:15px;
}
button:hover{
    background:#d95782;
}
</style>

</head>
<body>

<div class="card">
    <h2>Edit Booking</h2>

    <form method="post">
        <table class="table-input">
            <tr>
                <td>Fullname</td>
                <td><input type="text" name="fullname" value="<?php echo $booking['fullname']; ?>"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" value="<?php echo $booking['email']; ?>"></td>
            </tr>
            <tr>
                <td>Service</td>
				<td><select name="service">
                <option value="" disabled>Select a service</option>

                    <option value="Haircut" <?php if ($booking['service'] == 'Haircut') echo 'selected'; ?>>Haircut</option>
                    <option value="Hair Coloring" <?php if ($booking['service'] == 'Hair Coloring') echo 'selected'; ?>>Hair Coloring</option>
                    <option value="Manicure" <?php if ($booking['service'] == 'Manicure') echo 'selected'; ?>>Manicure</option>
                    <option value="Pedicure" <?php if ($booking['service'] == 'Pedicure') echo 'selected'; ?>>Pedicure</option>
					<option value="Facial Treatment" <?php if ($booking['service'] == 'Facial Treatment') echo 'selected'; ?>>Facial Treatment</option></select></td>
            </tr>
            <tr>
                <td>Date</td>
                <td><input type="date" name="date" value="<?php echo $booking['date']; ?>"></td>
            </tr>
            <tr>
                <td>Time</td>
                <td><input type="time" name="time" value="<?php echo $booking['time']; ?>"></td>
            </tr>
            <tr>
                <td>Note</td>
                <td><input type="text" name="note" value="<?php echo $booking['note']; ?>"></td>
            </tr>
        </table>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
