<?php
session_start();
include('connect.php');

$errors = array();

// ตรวจสอบการส่งข้อมูลการลงทะเบียน

if (isset($_POST['reg_user'])) {
    $room = mysqli_real_escape_string($conn, $_POST['Room_number']);
    $Full_name = mysqli_real_escape_string($conn, $_POST['name']);
    $Phone = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

// ตรวจสอบข้อมูลที่ส่งมา
    if (empty($room)) { 
        array_push($errors, "Room number is required"); }
    if (empty($Full_name)) {
         array_push($errors, "Full name is required"); }
    if (empty($Phone)) { 
        array_push($errors, "Phone number is required"); }
    if (empty($username)) {
         array_push($errors, "Username is required"); }
    if (empty($password)) {
         array_push($errors, "Password is required"); }

 // ตรวจสอบข้อมูลซ้ำในฐานข้อมูล
    $user_check_query = "SELECT * FROM user WHERE username = '$username' OR room = '$room' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['room'] === $room) {
            array_push($errors, "Room already exists");
        }
    }

// หากไม่มีข้อผิดพลาด บันทึกข้อมูลลงในฐานข้อมูล
    if (count($errors) == 0) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (room, Full_name, username, password, phone) 
                VALUES ('$room', '$Full_name', '$username', '$password_hash', '$Phone')";
        mysqli_query($conn, $sql);


 // เซ็ต session และ redirect ไปที่หน้า index.php
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are logged in";
        header('location: index.php');
        exit();
    } 
// หากมีข้อผิดพลาด กลับไปที่หน้า register.php พร้อมกับ error message   
    else {
        $_SESSION['error'] = "Failed to register";
        header("location: register.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class ="header">
        <h2>Register</h2>
    </div>
    <form method="post" action="register.php">
        <div class="input-group">
            <label for="room">Room number</label>
            <input type="text" name="Room_number">
        </div>
        <div class="input-group">
            <label for="name">Full name</label>
            <input type="text" name="name">
        </div>
        <div class="input-group">
            <label for="phone">Phone number</label>
            <input type="text" name="Phone_number">
        </div>
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" name="username">
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" name="password">
        </div>
        <div class="input-group">
            <button type="submit" name="reg_user" class="btn">Register</button>
        </div>
        <p>Already a member? <a href="login.php">Sign in</a></p>
    </form>
</body>
</html>
