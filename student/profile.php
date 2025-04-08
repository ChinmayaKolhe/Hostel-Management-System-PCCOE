<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$message = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $updateQuery = "UPDATE student SET Email=?, Phone=?, Address=? WHERE Student_ID=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $email, $phone, $address, $student_id);

    if ($stmt->execute()) {
        $message = "Profile updated successfully.";
    } else {
        $message = "Error updating profile.";
    }
}

// Fetch student data
$query = "SELECT s.*, c.City_Name, st.State_Name 
          FROM student s 
          LEFT JOIN city c ON s.City_ID = c.City_ID 
          LEFT JOIN state st ON s.State_ID = st.State_ID 
          WHERE Student_ID=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f7f7;
            padding: 30px;
        }
        .profile-container {
            max-width: 700px;
            background-color: #fff;
            padding: 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #444;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #0c7b93;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0a5c6b;
        }
        .info-group {
            margin-bottom: 10px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 6px;
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
    <div class="profile-container">
        <h2>Student Profile</h2>
        <a href="../student/dashboard.php" class="dashboard-btn">← Back to Dashboard</a>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="info-group">
                <label>Full Name</label>
                <input type="text" value="<?= $student['First_Name'] . ' ' . $student['Last_Name']; ?>" disabled>
            </div>
            <div class="info-group">
                <label>Date of Birth</label>
                <input type="text" value="<?= $student['DOB']; ?>" disabled>
            </div>
            <div class="info-group">
                <label>Course</label>
                <input type="text" value="<?= $student['Course']; ?>" disabled>
            </div>
            <div class="info-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= $student['Email']; ?>" required>
            </div>
            <div class="info-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= $student['Phone']; ?>" required>
            </div>
            <div class="info-group">
                <label>City</label>
                <input type="text" value="<?= $student['City_Name']; ?>" disabled>
            </div>
            <div class="info-group">
                <label>State</label>
                <input type="text" value="<?= $student['State_Name']; ?>" disabled>
            </div>
            <div class="info-group">
                <label>Address</label>
                <textarea name="address" rows="3"><?= $student['Address']; ?></textarea>
            </div>

            <button class="btn" type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
