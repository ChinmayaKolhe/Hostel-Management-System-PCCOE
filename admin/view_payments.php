<?php
session_start();
require_once('../includes/db_connect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['confirm']) && is_numeric($_GET['confirm'])) {
    $studentId = $_GET['confirm'];
    $update = $conn->prepare("UPDATE fees SET Remaining_Amount = 0, Payment_Status = 'Paid' WHERE Student_ID = ?");
    $update->bind_param("i", $studentId);
    $update->execute();
    header("Location: view_payments.php");
    exit();
}


$sql = "SELECT 
            f.Student_ID, 
            CONCAT(s.First_Name, ' ', s.Middle_Name, ' ', s.Last_Name) AS Full_Name, 
            f.Room_ID, 
            f.Total_Fees, 
            f.Remaining_Amount, 
            f.Payment_Status 
        FROM fees f 
        JOIN student s ON f.Student_ID = s.Student_ID";


$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Payments - Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #fdfdfd;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            color: #fff;
        }
        .btn-confirm {
            background-color: #28a745;
        }
        .btn-confirm:hover {
            background-color: #218838;
        }
        .status-paid {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .dashboard-btn {
  display: inline-block;
  background-color: #007bff;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  margin-bottom: 15px;
}
.dashboard-btn:hover {
  background-color: #0056b3;
}

    </style>
</head>
<body>
<div class="container">
<a href="../admin/dashboard.php" class="dashboard-btn">← Back to Dashboard</a>
    <h2>Payment Records</h2>
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Room ID</th>
                <th>Total Fees</th>
                <th>Remaining</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): 
                $status = $row['Remaining_Amount'] == 0 ? 'Paid' : 'Pending';
            ?>
            <tr>
                <td><?= $row['Student_ID'] ?></td>
                <td><?= htmlspecialchars($row['Full_Name']) ?></td>
                <td><?= $row['Room_ID'] ?? 'Not Booked' ?></td>
                <td>₹<?= number_format($row['Total_Fees'], 2) ?></td>
                <td>₹<?= number_format($row['Remaining_Amount'], 2) ?></td>
                <td class="<?= $status == 'Paid' ? 'status-paid' : 'status-pending' ?>">
                    <?= $status ?>
                </td>
                <td>
                    <?php if ($status === 'Pending'): ?>
                        <a href="view_payments.php?confirm=<?= $row['Student_ID'] ?>" class="btn btn-confirm">Confirm Payment</a>
                    <?php else: ?>
                        ✔ Confirmed
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
