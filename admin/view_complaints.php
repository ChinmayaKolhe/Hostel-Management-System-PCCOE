<?php
include '../includes/db_connect.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$result = mysqli_query($conn, "SELECT * FROM complaints JOIN student ON complaints.student_id = student.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Complaints</title>
    <style>
        body { font-family: Arial; background: #f4f7f9; padding: 30px; }
        table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: white; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>
    <h2>Student Complaints</h2>
    <table>
        <tr>
            <th>Student</th><th>Issue</th><th>Status</th><th>Date</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['fullname'] ?></td>
            <td><?= $row['message'] ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td><?= $row['complaint_date'] ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
