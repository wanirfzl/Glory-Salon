<?php
session_start();
include 'db_connect.php';

// Redirect kalau bukan customer
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Customer') {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['email'];

// Dapatkan info user
$user = $conn->query("SELECT * FROM users WHERE email='$email'")->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email_new = $_POST['email'];

    // Handle profile picture upload
    $profile_pic = $user['profile_pic']; // default ke gambar lama
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $targetDir = "images/profiles/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $filenameNew = 'profile_'.$_SESSION['user_id'].'.'.$ext;
        $targetFile = $targetDir . $filenameNew;

        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)){
            $profile_pic = $targetFile;
        }
    }

    // Update database
    $stmt = $conn->prepare("UPDATE users SET fullname=?, username=?, email=?, profile_pic=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $fullname, $username, $email_new, $profile_pic, $user['user_id']);
    if($stmt->execute()){
        $_SESSION['message'] = "Profile updated successfully!";
        $_SESSION['email'] = $email_new;
        $_SESSION['username'] = $username;
        header("Location: customer_dashboard.php?section=profile");
        exit();
    } else {
        $error = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile | Glory Salon</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif; background:#f7f7f7; margin:0; padding:30px;}
.card{max-width:600px; margin:auto; background:white; padding:30px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1);}
h2{color:#f06292; margin-bottom:20px;}
input, button{width:100%; padding:12px; margin:10px 0; border-radius:6px; border:1px solid #ddd;}
button{background:#4caf50; color:white; font-weight:600; cursor:pointer; border:none;}
button:hover{background:#43a047;}
img{max-width:150px; border-radius:50%; margin-bottom:10px; display:block;}
</style>
</head>
<body>

<div class="card">
<h2>Edit Profile</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post" enctype="multipart/form-data">
    <label>Profile Picture</label><br>
    <img src="<?php echo !empty($user['profile_pic']) ? $user['profile_pic'] : 'images/default-profile.jpg'; ?>" alt="Profile Picture" id="profilePreview"><br>
    <input type="file" name="profile_pic" accept="image/*" onchange="previewProfilePic(event)">

    <label>Full Name</label>
    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>

    <label>Username</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

    <button type="submit">Save Changes</button>
</form>
</div>

<script>
// Preview selected profile picture
function previewProfilePic(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('profilePreview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
