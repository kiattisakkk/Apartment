<?php 
    session_start();
    include('connect.php');

    // ตรวจสอบการล็อกอิน
    if (isset($_SESSION['username'])) {
        header('location: index.php');
        exit();
    }

    $errors = array(); // เพิ่มตัวแปร errors เพื่อใช้เก็บข้อผิดพลาด

    if (isset($_POST['login_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($password)) { array_push($errors, "Password is required"); }

        if (count($errors) == 0) {
            $query = "SELECT * FROM user WHERE username='$username' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $user = mysqli_fetch_assoc($result);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $username;
                    $_SESSION['success'] = "You are now logged in";
                    header("location: index.php");
                    exit();
                } else {
                    array_push($errors, "Wrong username/password combination");
                }
            } else {
                array_push($errors, "Database error: " . mysqli_error($conn));
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Login</h2>
    </div>
    <form method="post" action="login.php">
        <?php include('errors.php'); ?> <!-- แสดงข้อผิดพลาดถ้ามี -->
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" name="username">
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" name="password">
        </div>
        <div class="input-group">
           <button type="submit" name="login_user" class="btn">Login</button>
        </div>
        <p>Not yet a member? <a href="register.php">Sign Up</a></p>
    </form>
</body>
</html>
