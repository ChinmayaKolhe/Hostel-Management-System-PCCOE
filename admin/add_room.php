<?php
session_start();
include '../includes/db_connect.php';

$message = "";

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $room_no = mysqli_real_escape_string($conn, $_POST['room_no']);
  $seater = mysqli_real_escape_string($conn, $_POST['seater']);
  $fees = mysqli_real_escape_string($conn, $_POST['fees']);
  $date = date('Y-m-d');

  $insert = "INSERT INTO rooms (Room_No, Seater, Fees, Posting_Date) 
             VALUES ('$room_no', '$seater', '$fees', '$date')";

  if (mysqli_query($conn, $insert)) {
    $message = "Room added successfully!";
  } else {
    $message = "Error: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Room</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f4f8;
      padding: 40px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    form {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }
    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #218838;
    }
    .message {
      text-align: center;
      margin-top: 15px;
      color: green;
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
    }
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h2>Add New Room</h2>

  <?php if ($message): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="room_no">Room Number</label>
    <input type="text" name="room_no" id="room_no" required>

    <label for="seater">Seater</label>
    <input type="number" name="seater" id="seater" min="1" required>

    <label for="fees">Fees</label>
    <input type="number" name="fees" id="fees" min="0" required>

    <button type="submit">Add Room</button>
  </form>

  <a href="manage_rooms.php" class="back-link">← Back to Manage Rooms</a>
</body>
</html>
