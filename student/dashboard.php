<?php
session_start();
require_once "../includes/db_connect.php"; // Update path as needed

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student data
$query = "SELECT First_Name, Last_Name FROM student WHERE Student_ID = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

$full_name = $student ? $student['First_Name'] . " " . $student['Last_Name'] : "Student";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../assets/style.css"> <!-- Optional external CSS -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: #f2f6fc;
    }

    .header {
      background: #3f51b5;
      color: white;
      padding: 20px 30px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .dashboard-container {
      padding: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      max-width: 1100px;
      margin: auto;
    }

    .card {
      background: white;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      margin-top: 0;
      font-size: 20px;
      color: #3f51b5;
    }

    .card a {
      display: inline-block;
      margin-top: 10px;
      text-decoration: none;
      color: #2196f3;
      font-weight: bold;
    }

    .card a:hover {
      text-decoration: underline;
    }

    .logout {
      position: absolute;
      top: 20px;
      right: 30px;
      background: #e53935;
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .logout:hover {
      background: #d32f2f;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Welcome, <?php echo htmlspecialchars($full_name); ?> 👋</h1>
    <form action="logout.php" method="post" style="position: absolute; top: 20px; right: 20px;">
      <button type="submit" class="logout">Logout</button>
    </form>
  </div>

  <div class="dashboard-container">
    <div class="card">
      <h3>📄 Mess Registration</h3>
      <p>Choose or update your mess preferences.</p>
      <a href="mess_registration.php">Register Now</a>
    </div>

    <div class="card">
      <h3>🏠 Room Booking</h3>
      <p>Select your preferred room based on seater & fees.</p>
      <a href="rooms_booking.php">Book Room</a>
    </div>

    <div class="card">
      <h3>💰 Fee Details</h3>
      <p>View your fee payment history and due amount.</p>
      <a href="payments.php">Check Now</a>
    </div>

    <div class="card">
      <h3>📝 Profile</h3>
      <p>View or edit your student profile details.</p>
      <a href="profile.php">Go to Profile</a>
    </div>

    <!-- ✅ NEW FUNCTIONALITY CARD -->
    <div class="card">
      <h3>📋 My Selections</h3>
      <p>View your registered mess and selected room info.</p>
      <a href="view_registration.php">View Details</a>
    </div>
  </div>

</body>
</html>
