<?php
include 'db_connect.php';
$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE user_id='$id'")->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $conn->query("UPDATE users SET 
        fullname='$fullname',
        email='$email',
        username='$username',
        role='$role'
    WHERE user_id='$id'");

    session_start();
    $_SESSION['message'] = "User updated successfully!";
    header("Location: admin_dashboard.php?section=users");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
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
    width:150px;
}
input, select{
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
    <h2>Edit User</h2>

    <form method="post">
        <table class="table-input">
            <tr>
                <td>Fullname</td>
                <td><input type="text" name="fullname" value="<?php echo $user['fullname']; ?>"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" value="<?php echo $user['email']; ?>"></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" value="<?php echo $user['username']; ?>"></td>
            </tr>
            <tr>
                <td>Role</td>
                <td>
                    <select name="role">
                        <option value="Admin" <?php if($user['role']=='Admin') echo 'selected'; ?>>Admin</option>
                        <option value="Customer" <?php if($user['role']=='Customer') echo 'selected'; ?>>Customer</option>
                    </select>
                </td>
            </tr>
        </table>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
