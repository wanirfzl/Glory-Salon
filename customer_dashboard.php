<?php
session_start();
include 'db_connect.php';

// Redirect kalau bukan customer
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Customer') {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['email'];
$username = $_SESSION['username'];

// Dapatkan booking history
$bookings = $conn->query("SELECT * FROM bookings WHERE email='$email' ORDER BY date DESC");

// Optional: profile info
$profile = $conn->query("SELECT * FROM users WHERE email='$email'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard | Glory Salon</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif; background:#f7f7f7; margin:0;}
header{background:#fff; padding:12px 24px; box-shadow:0 2px 6px rgba(0,0,0,0.1); display:flex; justify-content:space-between; align-items:center;}
header h1{color:#f06292;}
nav{display:flex; gap:20px;}
nav a{color:#555; font-weight:600; text-decoration:none; padding:8px 12px; border-radius:6px; transition:0.2s;}
nav a.active, nav a:hover{background:#f06292; color:white;}
.main{padding:30px;}
section{display:none;}
section.active{display:block;}
table{width:100%; border-collapse:collapse; margin-top:20px; background:white; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05);}
th, td{padding:12px; text-align:center; border-bottom:1px solid #eee;}
th{background:#fce4ec; color:#f06292;}
tr:nth-child(even){background:#fafafa;}
button{padding:6px 12px; border:none; border-radius:5px; cursor:pointer; font-weight:600;}
.btn-edit {
    background: #4caf50;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    border: none;
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
    transition: 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.btn-edit:hover {
    background: #43a047;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.btn-cancel {
    background: #f44336;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-block;
    transition: 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.btn-cancel:hover {
    background: #e53935;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
/* Profile Card */
.profile-card {
    background: white;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    max-width: 800px;
    margin: 20px auto;
}
.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}
.profile-header img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f06292;
}
.profile-header h3 {
    font-size: 28px;
    color: #f06292;
    margin: 0;
}
.profile-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px 30px;
}
.profile-info div {
    background: #fff0f5;
    padding: 12px 15px;
    border-radius: 8px;
    font-weight: 500;
}
.profile-info div strong {
    display: block;
    color: #f06292;
    margin-bottom: 5px;
}
.btn-edit-profile {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: #4caf50;
    color: white;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.btn-edit-profile:hover {
    background: #43a047;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
/* Toast Notification */
#toast{
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
}
</style>
</head>
<body>

<!-- Toast -->
<div id="toast"></div>

<header>
    <h1>Welcome, <?php echo $username; ?></h1>
    <nav>
        <a href="#" class="active" onclick="showSection('bookings')">My Bookings</a>
        <a href="#" onclick="showSection('profile')">My Profile</a>
        <a href="index.html">Logout</a>
    </nav>
</header>

<div class="main">
    <!-- Booking History -->
    <section id="bookings" class="active">
        <h2>My Booking History</h2>
        <button onclick="location.href='booking.html'">Add New Booking</button>
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; while($row = $bookings->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['service']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo isset($row['status']) ? $row['status'] : 'Pending'; ?></td>
                    <td>
                        <a class="btn-edit" href="edit_customer_booking.php?id=<?php echo $row['booking_id']; ?>">Edit</a>
                        <a class="btn-cancel" href="delete_customer_booking.php?id=<?php echo $row['booking_id']; ?>" onclick="return confirm('Cancel this booking?')">Cancel</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- Profile Section -->
<section id="profile">
    <div class="profile-card">
        <form method="post" action="edit_customer_profile.php" enctype="multipart/form-data">
            <div class="profile-header">
                <img src="<?php echo !empty($profile['profile_pic']) ? $profile['profile_pic'] : 'images/default-profile.jpg'; ?>" alt="Profile Picture" id="profilePreview">
                <div>
                    <input type="file" name="profile_pic" accept="image/*" onchange="previewProfilePic(event)">
                </div>
            </div>
            <div class="profile-info">
                <div>
                    <strong>Full Name</strong>
                    <input type="text" name="fullname" value="<?php echo $profile['fullname']; ?>" required>
                </div>
                <div>
                    <strong>Email</strong>
                    <input type="email" name="email" value="<?php echo $profile['email']; ?>" required>
                </div>
                <div>
                    <strong>Username</strong>
                    <input type="text" name="username" value="<?php echo $profile['username']; ?>" required>
                </div>
                <div>
                    <strong>Phone</strong>
                    <input type="text" name="phone" value="<?php echo isset($profile['phone']) ? $profile['phone'] : ''; ?>">
                </div>
            </div>
            <button type="submit" class="btn-edit-profile">Save Changes</button>
        </form>
    </div>
</section>

<script>
// Preview selected profile picture
function previewProfilePic(event){
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('profilePreview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>


<script>
// Section toggle
function showSection(sectionId){
    document.querySelectorAll('section').forEach(s=>s.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');

    document.querySelectorAll('nav a').forEach(a=>a.classList.remove('active'));
    document.querySelector('nav a[onclick="showSection(\''+sectionId+'\')"]').classList.add('active');
}

// Toast function
function showToast(message){
    const toast = document.getElementById('toast');
    toast.innerText = message;
    toast.style.display = 'block';
    setTimeout(()=>{toast.style.display='none';}, 3000);
}

// Optional: show toast from PHP session
<?php
if(isset($_SESSION['message'])){
    echo "showToast('".$_SESSION['message']."');";
    unset($_SESSION['message']);
}
?>
</script>

</body>
</html>
