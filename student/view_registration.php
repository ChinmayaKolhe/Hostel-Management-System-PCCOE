<?php
session_start();
require_once "../includes/db_connect.php";

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Registrations</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }

    h2 {
      color: #3f51b5;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: #3f51b5;
      color: white;
    }

    tr:hover {
      background: #f1f1f1;
    }

    .back-btn {
      padding: 10px 16px;
      background: #3f51b5;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .back-btn:hover {
      background: #303f9f;
    }

    .no-data {
      text-align: center;
      color: #999;
      font-style: italic;
    }
  </style>
</head>
<body>

  <h2>Mess Registration Details</h2>
  <table>
    <tr>
      <th>Mess Name</th>
      <th>Type</th>
      <th>Fee</th>
    </tr>

    <?php
    $mess_query = "
      SELECT m.Name AS Mess_Name, m.Options AS Mess_Type, m.Mess_Fee 
      FROM mess_registration mr
      JOIN mess m ON mr.Mess_ID = m.Mess_ID
      WHERE mr.Student_ID = ?
    ";

    $stmt = $conn->prepare($mess_query);
    if ($stmt) {
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Mess_Name']}</td>
                        <td>{$row['Mess_Type']}</td>
                        <td>₹ {$row['Mess_Fee']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='no-data'>No mess registration found.</td></tr>";
        }
        $stmt->close();
    } else {
        echo "<tr><td colspan='3' class='no-data'>Error fetching mess registration.</td></tr>";
    }
    ?>
  </table>
  <h2>Room Booking Details</h2>
<table>
  <tr>
    <th>Room Number</th>
    <th>Seater</th>
    <th>Fees</th>
  </tr>

  <?php
  $room_query = "
    SELECT r.Room_No, r.Seater, r.Fees 
    FROM room_registration rr
    JOIN rooms r ON rr.Room_ID = r.Room_ID
    WHERE rr.Student_ID = ?
  ";

  $stmt = $conn->prepare($room_query);
  if ($stmt) {
      $stmt->bind_param("i", $student_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['Room_No']}</td>
                      <td>{$row['Seater']}</td>
                      <td>₹ {$row['Fees']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='3'>No room booking found.</td></tr>";
      }

      $stmt->close();
  } else {
      echo "<tr><td colspan='3'>SQL Error: " . $conn->error . "</td></tr>";
  }
  ?>
</table>


  <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

</body>
</html>
