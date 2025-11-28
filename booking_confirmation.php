<?php
session_start();
if(!isset($_SESSION['booking'])){
    header('Location: booking.html');
    exit();
}
$booking = $_SESSION['booking'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Confirmation | Glory Salon</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Great+Vibes&display=swap" rel="stylesheet">

<style>
  body {
    font-family: Poppins, sans-serif;
    background-color: #fff5f8;
    margin: 0;
    padding: 0;
    color: #333;
  }

  /* HEADER */
  header {
    background-color: #f48fb1;
    color: white;
    padding: 8px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
  }

  .left-header {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .logo-img {
    height: 75px;
    width: auto;
    border-radius: 10px;
  }

  .site-title {
    font-family: 'Great Vibes', cursive;
    font-size: 46px;
    letter-spacing: 4px;
    margin: 0;
    color: white;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
  }

  nav a {
    color: white;
    text-decoration: none;
    margin: 0 15px;
    font-weight: 700;
    font-size: 18px;
    letter-spacing: 0.5px;
    transition: 0.2s;
  }

  nav a:hover {
    opacity: 0.8;
  }

  /* MAIN CONTAINER */
  .container {
    max-width: 700px;
    background: white;
    margin: 50px auto;
    padding: 40px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }

  h2 {
    color: #ec407a;
    margin-bottom: 15px;
  }

  .success-icon {
    font-size: 70px;
    color: #4caf50;
    margin-bottom: 20px;
  }

  .details-box {
    text-align: left;
    background: #fff0f4;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
    border: 1px solid #f8c6d5;
  }

  .details-box p {
    font-size: 16px;
    margin: 8px 0;
  }

  .btn {
    display: block;
    width: 100%;
    background: #f48fb1;
    color: white;
    padding: 12px;
    border-radius: 5px;
    font-size: 16px;
    margin-top: 25px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.2s;
  }

  .btn:hover {
    background-color: #d81b60;
  }

  footer {
    background-color: #f48fb1;
    color: white;
    text-align: center;
    padding: 10px;
    margin-top: 40px;
  }
</style>
</head>
<body>

<header>
  <div class="left-header">
    <img src="images/logo.jpg" class="logo-img" alt="Glory Salon Logo">
    <h1 class="site-title">Glory Salon</h1>
  </div>
  <nav>
    <a href="index.html">Home</a>
    <a href="services.html">Services</a>
    <a href="booking.html">Book Now</a>
    <a href="login.html">Login</a>
  </nav>
</header>

<div class="container">
  <div class="success-icon">✔</div>
  <h2>Booking Confirmed!</h2>
  <p>Thank you for booking with Glory Salon. Here are your appointment details:</p>

  <div class="details-box">
    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['fullname']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
    <p><strong>Service:</strong> <?php echo htmlspecialchars($booking['service']); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($booking['date']); ?></p>
    <p><strong>Time:</strong> <?php echo htmlspecialchars($booking['time']); ?></p>
    <p><strong>Notes:</strong> <?php echo htmlspecialchars($booking['note']); ?></p>
  </div>

  <a href="index.html" class="btn">Back to Home</a>
</div>

<footer>
  <p>© 2025 Glory Salon. All Rights Reserved.</p>
</footer>

</body>
</html>
<?php unset($_SESSION['booking']); ?>
