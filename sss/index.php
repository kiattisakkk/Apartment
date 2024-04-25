<?php 
    session_start();
    if (!isset($_SESSION['username'])) {
        header('location: login.php'); 
        exit();
    } 

    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        session_destroy();
        unset($_SESSION['username']);
        header('location: login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Home Page</h2>
    </div>
    <div class="content">

        <?php if (isset($_SESSION['success'])):?>
            <div class="success">
                <h3>
                    <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </h3>

            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['username'])): ?>
            <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
            <p><a href="index.php?logout=1" style="color: red;">Logout</a></p>
        <?php endif; ?>
    </div>
    <div class="container">
        <h1>Electricity and Water Calculation</h1>
        <!-- Form for input -->
        <form action="calculate.php" method="GET">
            <label for="year">Year:</label>
            <input type="text" name="year" id="year" value="<?php echo date('Y'); ?>">
            <label for="month">Month:</label>
            <input type="text" name="month" id="month" value="<?php echo date('m'); ?>">
            <button type="submit">Calculate</button> <!-- ปุ่มสำหรับคำนวณ -->
        </form>

        <!-- Result section -->
        <div class="result">
            <h2>Calculation Result</h2>
            <?php include('calculate.php'); ?>
        </div>
    </div>
</body>
</html>
