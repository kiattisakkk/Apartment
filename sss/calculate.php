<?php
session_start();
include('connect.php');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
if (strlen($month) == 1) {
    $month = '0' . $month;
}
$current_year_month = $year . '-' . $month;
$prev_year_month = date('Y-m', strtotime('-1 months', strtotime($current_year_month)));

try {
    // สร้างคำสั่ง SQL
    $sql = "SELECT room_id,
                    SUM( IF( DATE_FORMAT( date_record, '%Y-%m' ) = '$prev_year_month', water, 0 ) ) AS prev_water,
                    SUM( IF( DATE_FORMAT( date_record, '%Y-%m' ) = '$current_year_month', water, 0 ) ) AS current_water,
                    SUM( IF( DATE_FORMAT( date_record, '%Y-%m' ) = '$prev_year_month', elect, 0 ) ) AS prev_elect,
                    SUM( IF( DATE_FORMAT( date_record, '%Y-%m' ) = '$current_year_month', elect, 0 ) ) AS current_elect
            FROM tb_meter_list
            WHERE DATE_FORMAT( date_record, '%Y-%m' ) IN ('$current_year_month', '$prev_year_month')
            GROUP BY room_id";

    // ส่งคำสั่ง SQL ไปทำงาน
    $result = mysqli_query($conn, $sql);

    // เช็คผลลัพธ์
    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    mysqli_close($conn);
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>

<!-- เริ่มต้นของ HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   
    <style>
        .highlight {
            background-color: #FFFF88;
        }
        .red_text {
            color: red;
        }
        table th, table td {
            text-align: center !important;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <form class="form-horizontal" method="GET" action="meter_list.php">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">เลือกเดือน : </label>
                <input type="text" class="form-control" name="year" placeholder="เดือน" value="<?php echo $year;?>">
                <input type="text" class="form-control" name="month" placeholder="เดือน" value="<?php echo $month;?>">
                <input type="submit" value="ประมวลผล" />
            </div>
        </form>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th rowspan=2>ห้อง</th>
                        <th colspan=2>ค่าน้ำ</th>
                        <th colspan=2>ค่าไฟ</th>
                    </tr>
                    <tr>
                        <td align="center">เดือนที่แล้ว</td>
                        <td align="center">เดือนปัจจุบัน</td>
                        <td align="center">เดือนที่แล้ว</td>
                        <td align="center">เดือนปัจจุบัน</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $row['room_id']; ?></td>
                            <td><?php echo $row['prev_water']; ?></td>
                            <td><?php echo $row['current_water']; ?></td>
                            <td><?php echo $row['prev_elect']; ?></td>
                            <td><?php echo $row['current_elect']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>
