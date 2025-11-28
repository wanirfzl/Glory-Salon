<?php
session_start();
include 'db_connect.php';

// Redirect jika bukan admin/staff
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'Customer') {
    header("Location: login.html");
    exit();
}

// Dapatkan total bookings dan users
$totalBookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];
$bookings = $conn->query("SELECT * FROM bookings ORDER BY booking_id DESC");

// Dapatkan semua users
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$users = $conn->query("SELECT * FROM users ORDER BY user_id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Glory Salon</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
<style>
/* ======= RESET & BASE ======= */
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Poppins', sans-serif;background:#f7f7f7;color:#333;}
a{text-decoration:none;color:inherit;}
ul{list-style:none;}
header{display:flex; justify-content:space-between; align-items:center; padding:12px 24px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1); position:sticky; top:0; z-index:1000;}
.header-left{display:flex; align-items:center; gap:12px;}
.header-left img{height:50px; width:auto; border-radius:8px;}
.header-left h1{font-family:'Great Vibes', cursive; font-size:32px; color:#f06292;}
.sidebar{position:fixed; top:0; left:0; height:100%; width:220px; background:#fff; border-right:1px solid #ddd; padding-top:80px;}
.sidebar a{display:block; padding:14px 20px; margin:6px 0; color:#555; font-weight:600; border-radius:8px; transition:0.2s;}
.sidebar a:hover{background:#fce4ec; color:#f06292;}
.sidebar a.active{background:#f06292; color:white;}
.main{margin-left:240px; padding:30px;}
.cards{display:flex; gap:20px; flex-wrap:wrap;}
.card{flex:1; min-width:200px; background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08);}
.card h3{font-size:20px; color:#f06292; margin-bottom:10px;}
.card p{font-size:16px;}
table{width:100%; border-collapse:collapse; margin-top:20px;}
th, td{padding:12px; text-align:center; border-bottom:1px solid #eee;}
th{background:#fce4ec; color:#f06292;}
tr:nth-child(even){background:#fafafa;}
button{padding:6px 12px; border:none; border-radius:5px; cursor:pointer; font-weight:600;}
.btn-edit{background:#4caf50; color:white;}
.btn-edit:hover{background:#388e3c;}
.btn-delete{background:#f44336;color:white;}
.btn-delete:hover{background:#d32f2f;}
.modal{display:none;position:fixed; z-index:10000; left:0; top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);}
.modal-content{background:white; margin:80px auto; padding:20px; border-radius:10px; width:400px; position:relative;}
.close{position:absolute; top:12px; right:16px; font-size:24px; cursor:pointer; color:#f44336;}
</style>
</head>
<body>
	
<!-- Toast notification -->
<div id="toast" style="
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #d4edda;
    color: #155724;
    padding: 12px 20px;
    border-radius: 6px;
    border: 1px solid #c3e6cb;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    display: none;
    z-index: 10000;
    font-weight: 600;
">
</div>
	
<?php
if (isset($_SESSION['message'])) {
    echo "<script>
        const toast = document.getElementById('toast');
        toast.innerText = '".$_SESSION['message']."';
        toast.style.display = 'block';

        // Hide selepas 3 saat
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    </script>";
    unset($_SESSION['message']);
}
?>
		
	

<header>
  <div class="header-left">
    <img src="images/logo.jpg" alt="Logo">
    <h1>Glory Salon</h1>
  </div>
</header>

<div class="sidebar">
  <a href="#" class="active" onclick="showSection('dashboard')">Dashboard</a>
  <a href="#" onclick="showSection('bookings')">Manage Bookings</a>
  <a href="#" onclick="showSection('users')">Manage Users</a>
  <a href="#" onclick="showSection('reports')">Reports</a>
  <a href="#" onclick="showSection('settings')">Settings</a>
  <a href="index.html">Logout</a>
</div>

<div class="main">

  <!-- DASHBOARD -->
  <section id="dashboard">
    <div class="cards">
      <div class="card">
        <h3>Total Bookings</h3>
        <p><?php echo $totalBookings; ?></p>
      </div>
      <div class="card">
        <h3>Total Users</h3>
        <p><?php echo $totalUsers; ?></p>
      </div>
      <div class="card">
        <h3>Revenue Estimate</h3>
        <p>RM 3,200</p>
      </div>
      <div class="card">
        <h3>Pending Bookings</h3>
        <p>5</p>
      </div>
    </div>
  </section>

  <!-- MANAGE BOOKINGS -->
	
  <section id="bookings" style="display:none;">
	  <?php
if (isset($_SESSION['message'])) {
    echo "<div style='background:#d4edda; 
                       color:#155724; 
                       padding:12px; 
                       border-radius:6px; 
                       margin-bottom:15px; 
                       border:1px solid #c3e6cb;'>
            ".$_SESSION['message']."
          </div>";

    unset($_SESSION['message']); 
}
?>
    <h2>Manage Bookings</h2>
    <button onclick="location.href='booking.html'">Add Booking</button>
    <table>
      <thead>
        <tr>
          <th>No</th><th>Fullname</th><th>Email</th><th>Service</th><th>Date</th><th>Time</th><th>Note</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; while($row = $bookings->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $row['fullname']; ?></td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['service']; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo $row['time']; ?></td>
          <td><?php echo $row['note']; ?></td>
          <td>
            <button class="btn-edit" onclick="location.href='edit_booking.php?id=<?php echo $row['booking_id']; ?>'">Edit</button>
            <button class="btn-delete" onclick="if(confirm('Delete this booking?')) location.href='delete_booking.php?id=<?php echo $row['booking_id']; ?>'">Delete</button>

          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>

  <!-- MANAGE USERS -->
  <section id="users" style="display:none;">
    <h2>Manage Users</h2>
    <button onclick="location.href='register.html'">Add User</button>
    <table>
      <thead>
        <tr>
          <th>No</th><th>Fullname</th><th>Email</th><th>Username</th><th>Role</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; while($row = $users->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $row['fullname']; ?></td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['username']; ?></td>
          <td><?php echo $row['role']; ?></td>
          <td>
            <button class="btn-edit" onclick="location.href='edit_user.php?id=<?php echo $row['user_id']; ?>'">Edit</button>
            <button class="btn-delete" onclick="if(confirm('Delete this user?')) location.href='delete_user.php?id=<?php echo $row['user_id']; ?>'">Delete</button>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>

	
	
  <!-- REPORTS -->
<section id="reports" style="display:none;">
  <h2>Reports</h2>

  <!-- CHART -->
  <div class="chart" style="width:100%; max-width:700px; margin:20px auto;">
    <canvas id="bookingChart"></canvas>
  </div>

  <!-- REPORT TABLE -->
  <table>
    <thead>
      <tr><th>Month</th><th>Total Bookings</th><th>Total Revenue</th></tr>
    </thead>
    <tbody>
      <tr><td>Jan</td><td>20</td><td>RM 2,000</td></tr>
      <tr><td>Feb</td><td>15</td><td>RM 1,500</td></tr>
      <tr><td>Mar</td><td>25</td><td>RM 3,200</td></tr>
      <tr><td>Apr</td><td>10</td><td>RM 1,000</td></tr>
      <tr><td>May</td><td>30</td><td>RM 3,500</td></tr>
    </tbody>
  </table>
</section>

<!-- CHART.JS SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('bookingChart').getContext('2d');
const bookingChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May'],
        datasets: [{
            label: 'Total Bookings',
            data: [20,15,25,10,30],
            backgroundColor: '#f06292'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
},
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

	
	
    <!-- SETTINGS -->
<section id="settings" style="display:none;">
  <h2>Settings</h2>

  <!-- ADMIN PROFILE -->
  <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); margin-bottom:20px;">
    <h3>Admin Profile</h3>
    <label>Full Name:</label>
    <input type="text" placeholder="Admin Name" style="width:100%; padding:8px; margin:8px 0; border-radius:6px; border:1px solid #ddd;">
    
    <label>Email:</label>
    <input type="email" placeholder="admin@example.com" style="width:100%; padding:8px; margin:8px 0; border-radius:6px; border:1px solid #ddd;">
    
    <label>Profile Picture:</label>
    <input type="file" style="margin:8px 0;">
  </div>

  <!-- CHANGE PASSWORD -->
  <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); margin-bottom:20px;">
    <h3>Change Password</h3>
    <label>Current Password:</label>
    <input type="password" placeholder="Current Password" style="width:100%; padding:8px; margin:8px 0; border-radius:6px; border:1px solid #ddd;">
    
    <label>New Password:</label>
    <input type="password" placeholder="New Password" style="width:100%; padding:8px; margin:8px 0; border-radius:6px; border:1px solid #ddd;">
    
    <label>Confirm New Password:</label>
    <input type="password" placeholder="Confirm New Password" style="width:100%; padding:8px; margin:8px 0; border-radius:6px; border:1px solid #ddd;">
  </div>

  <!-- NOTIFICATIONS -->
  <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); margin-bottom:20px;">
    <h3>Notification Preferences</h3>
    <label><input type="checkbox" checked> Receive email notifications</label><br>
    <label><input type="checkbox"> Receive SMS notifications</label><br>
    <label><input type="checkbox" checked> Weekly summary report</label>
  </div>

  <!-- THEME OPTIONS -->
  <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); margin-bottom:20px;">
    <h3>Theme Options</h3>
    <label>Primary Color:</label>
    <input type="color" value="#f06292" style="margin-top:8px;">
    <div style="margin-top:10px;">
      <label><input type="radio" name="theme" checked> Light Mode</label><br>
      <label><input type="radio" name="theme"> Dark Mode</label>
    </div>
  </div>

  <!-- SAVE BUTTON -->
  <button style="margin-top:20px; padding:12px 20px; background:#f06292; color:white; font-weight:600; border:none; border-radius:8px; cursor:pointer;">Save Settings</button>
</section>

</div>

<script>
function showSection(sectionId){
    const sections = document.querySelectorAll('.main > section');
    sections.forEach(sec => sec.style.display = 'none');
    document.getElementById(sectionId).style.display = 'block';

    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
    document.querySelector('.sidebar a[onclick="showSection(\''+sectionId+'\')"]').classList.add('active');
}


// Placeholder JS untuk modal/edit/delete
function openBookingModal(){ alert('Open Add Booking Modal'); }
function editBooking(id){ alert('Edit booking '+id); }
function deleteBooking(id){ alert('Delete booking '+id); }
function openUserModal(){ alert('Open Add User Modal'); }
function editUser(id){ alert('Edit user '+id); }
function deleteUser(id){ alert('Delete user '+id); }
</script>
	
<script>
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');

    if (section) {
        showSection(section);
    }
};
</script>	
	
</body>
</html>
