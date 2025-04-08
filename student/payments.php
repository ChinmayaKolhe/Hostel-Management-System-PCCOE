<?php
session_start();
require_once('../includes/db_connect.php');

if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$studentId = $_SESSION['student_id'];

// Fetch payment details
$feeQuery = $conn->prepare("SELECT * FROM Fees WHERE Student_ID = ?");
$feeQuery->bind_param("i", $studentId);
$feeQuery->execute();
$feeResult = $feeQuery->get_result();
$feeData = $feeResult->fetch_assoc();

$totalFees = $feeData['Total_Fees'] ?? 0;
$remaining = $feeData['Remaining_Amount'] ?? 0;
$roomId = $feeData['Room_ID'] ?? 'N/A';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment History</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }

        .container {
            max-width: 750px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
        }

        th {
            background: #f8f9fa;
            color: #333;
        }

        .status {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
        }

        .paid {
            background: #d4edda;
            color: #155724;
        }

        .due {
            background: #f8d7da;
            color: #721c24;
        }

        a.btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s ease;
            text-align: center;
        }

        a.btn:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment History</h2>

        <table>
            <tr>
                <th>Room ID</th>
                <td><?= $roomId !== 'N/A' ? $roomId : 'Not Booked' ?></td>
            </tr>
            <tr>
                <th>Total Fees</th>
                <td>₹<?= number_format($totalFees, 2) ?></td>
            </tr>
            <tr>
                <th>Remaining Amount</th>
                <td>₹<?= number_format($remaining, 2) ?></td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>
                    <?php if ($remaining == 0 && $totalFees > 0): ?>
                        <span class="status paid">Fully Paid</span>
                    <?php elseif ($totalFees == 0): ?>
                        <span class="status">No Fees Yet</span>
                    <?php else: ?>
                        <span class="status due">Pending</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="back-link">
            <a href="dashboard.php" class="btn">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
