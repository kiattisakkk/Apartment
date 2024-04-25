<?php 
    session_start();
    include('connect.php');
    $errors = array();

    if (isset($_POST['login_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($password)) { array_push($errors, "Password is required"); }

        if (count($errors) == 0) {
            $query = "SELECT * FROM user WHERE username='$username' LIMIT 1";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in";
                header("location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Wrong username/password combination";
                header("location: login.php");
                exit();
            }
        }
    }
?>
