<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../includes/db_connect.php');

if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$studentId = $_SESSION['student_id'];
$message = '';

// Handle room booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'], $_POST['stay_from'], $_POST['duration'])) {
    $roomId = intval($_POST['room_id']);
    $stayFrom = $_POST['stay_from'];
    $duration = intval($_POST['duration']);

    // Check if student already booked a room
    $checkBooking = $conn->prepare("SELECT * FROM room_registration WHERE Student_ID = ?");
    $checkBooking->bind_param("i", $studentId);
    $checkBooking->execute();
    $existingBooking = $checkBooking->get_result();

    if ($existingBooking->num_rows > 0) {
        $message = "<div class='alert error'>You have already booked a room.</div>";
    } else {
        // Fetch room fee
        $feeQuery = $conn->prepare("SELECT Fees FROM rooms WHERE Room_ID = ?");
        $feeQuery->bind_param("i", $roomId);
        $feeQuery->execute();
        $feeResult = $feeQuery->get_result()->fetch_assoc();

        if ($feeResult) {
            $monthlyFee = $feeResult['Fees'];
            $totalFees = $monthlyFee * $duration;

            // Insert into room_registration table
            $insert = $conn->prepare("INSERT INTO room_registration (Student_ID, Room_ID, Registration_Date, Duration, Total_Fees, Remaining_Amount) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$insert) {
                $message = "<div class='alert error'>Prepare failed: " . $conn->error . "</div>";
            } else {
                $insert->bind_param("iisidd", $studentId, $roomId, $stayFrom, $duration, $totalFees, $totalFees);
                if ($insert->execute()) {
                    $message = "<div class='alert success'>Room booked successfully for ₹$totalFees!</div>";
                } else {
                    $message = "<div class='alert error'>Booking failed: " . $insert->error . "</div>";
                }
            }
        } else {
            $message = "<div class='alert error'>Invalid room selected.</div>";
        }
    }
}

// Get room availability
$roomQuery = "
    SELECT r.Room_ID, r.Seater, r.Room_No, r.Fees,
           (r.Seater - COUNT(rr.Registration_ID)) AS Available 
    FROM rooms r 
    LEFT JOIN room_registration rr 
        ON r.Room_ID = rr.Room_ID 
        AND DATE_ADD(rr.Registration_Date, INTERVAL rr.Duration MONTH) >= CURDATE()
    GROUP BY r.Room_ID
    ORDER BY r.Seater;
";

$rooms = $conn->query($roomQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 35px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 550px;
        }

        h2 {
            text-align: center;
            color: #1f4037;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 500;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        select, input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        input[type="submit"] {
            background: #1f4037;
            color: #fff;
            border: none;
            padding: 14px;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #14532d;
        }

        .alert {
            text-align: center;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #1f4037;
            font-weight: 500;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Room Booking</h2>
        <?= $message ?>

        <form method="POST">
            <div class="form-group">
                <label for="room_id">Select Room:</label>
                <select name="room_id" required>
                    <option value="">-- Select Room --</option>
                    <?php while ($room = $rooms->fetch_assoc()) { ?>
                        <option value="<?= $room['Room_ID'] ?>">
                            Room <?= $room['Room_No'] ?> | Seater: <?= $room['Seater'] ?> | Fee: ₹<?= $room['Fees'] ?>/month | Available Beds: <?= $room['Available'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="stay_from">Stay From:</label>
                <input type="date" name="stay_from" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="duration">Duration (in months):</label>
                <input type="number" name="duration" min="1" max="12" required>
            </div>

            <input type="submit" value="Book Room">
        </form>

        <div class="back-link">
            <p><a href="dashboard.php">← Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
