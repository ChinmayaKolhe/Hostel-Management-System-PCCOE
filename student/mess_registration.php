<?php
session_start();
require_once('../includes/db_connect.php');

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$studentId = $_SESSION['student_id'];
$message = "";

// Handle mess registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mess_id'])) {
    $messId = intval($_POST['mess_id']);

    // Check if student already registered in mess_registration table
    $check = $conn->prepare("SELECT * FROM mess_registration WHERE Student_ID = ?");
    $check->bind_param("i", $studentId);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $message = "<div class='alert error'>You have already registered for a mess.</div>";
    } else {
        // Get mess fee
        $feeStmt = $conn->prepare("SELECT Mess_Fee FROM Mess WHERE Mess_ID = ?");
        $feeStmt->bind_param("i", $messId);
        $feeStmt->execute();
        $feeResult = $feeStmt->get_result()->fetch_assoc();
        $messFee = $feeResult['Mess_Fee'];

        // Insert into mess_registration
        $insertMess = $conn->prepare("INSERT INTO mess_registration (Student_ID, Mess_ID) VALUES (?, ?)");
        $insertMess->bind_param("ii", $studentId, $messId);

        // Insert fee record with mess fee only
        $insertFee = $conn->prepare("INSERT INTO Fees (Student_ID, Room_ID, Total_Fees, Remaining_Amount) VALUES (?, NULL, ?, ?)");
        $insertFee->bind_param("idd", $studentId, $messFee, $messFee);

        if ($insertMess->execute() && $insertFee->execute()) {
            $message = "<div class='alert success'>Mess registered successfully. Fee: ₹$messFee</div>";
        } else {
            $message = "<div class='alert error'>Error registering for mess. Please try again.</div>";
        }
    }
}

// Fetch available mess options
$messQuery = "SELECT * FROM Mess WHERE Food_Status = 'Active'";
$messOptions = $conn->query($messQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mess Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #eef2f5;
            padding: 40px;
        }

        .container {
            background: #fff;
            max-width: 600px;
            margin: auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        label {
            font-weight: 600;
            color: #333;
        }

        select, input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 12px 0 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #27ae60;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #1e8449;
        }

        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 15px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }

        a {
            display: block;
            text-align: center;
            color: #2980b9;
            text-decoration: none;
            font-weight: 500;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }

        option {
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mess Registration</h2>
        <?= $message ?>

        <form method="POST" action="">
            <label for="mess_id">Select Mess Option:</label>
            <select name="mess_id" required>
                <option value="">-- Select Mess --</option>
                <?php while ($mess = $messOptions->fetch_assoc()) { ?>
                    <option value="<?= $mess['Mess_ID'] ?>">
                        <?= $mess['Name'] ?> | <?= $mess['Options'] ?> | ₹<?= $mess['Mess_Fee'] ?>
                    </option>
                <?php } ?>
            </select>

            <input type="submit" value="Register for Mess">
        </form>

        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>
