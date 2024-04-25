<?php
    session_start();
    include('connect.php');

    // ตรวจสอบการล็อกอิน
    if (!isset($_SESSION['username'])) {
        header('location: login.php');
        exit();
    }

    // ตรวจสอบการเข้าถึงหน้านี้โดยตรง
    if (!isset($_POST['reg_user'])) {
        header('location: register.php');
        exit();
    }

    $errors = array();

    // รับข้อมูลจากฟอร์มการลงทะเบียน
    $room = mysqli_real_escape_string($conn, $_POST['Room_number']);
    $Full_name = mysqli_real_escape_string($conn, $_POST['name']);
    $Phone = mysqli_real_escape_string($conn, $_POST['Phone_number']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // ตรวจสอบข้อมูลที่รับมา
    if (empty($room)) { array_push($errors, "Room number is required"); }
    if (empty($Full_name)) { array_push($errors, "Full name is required"); }
    if (empty($Phone)) { array_push($errors, "Phone number is required"); }
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }

    // ตรวจสอบข้อมูลซ้ำ
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

    // บันทึกข้อมูลใหม่หากไม่มีข้อผิดพลาด
    if (count($errors) == 0) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (room, Full_name, username, password, phone) 
                VALUES ('$room', '$Full_name', '$username', '$password_hash', '$Phone')";
        mysqli_query($conn, $sql);

        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are logged in";
        header('location: index.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to register";
        header("location: register.php");
        exit();
    }
?>
