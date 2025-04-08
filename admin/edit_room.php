<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
  header("Location: manage_rooms.php");
  exit();
}

$room_id = $_GET['id'];
$message = "";

// Fetch existing room data
$query = mysqli_query($conn, "SELECT * FROM rooms WHERE Room_ID = '$room_id'");
$room = mysqli_fetch_assoc($query);

if (!$room) {
  $message = "Room not found!";
} else {
  // Update on form submit
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_no = mysqli_real_escape_string($conn, $_POST['room_no']);
    $seater = mysqli_real_escape_string($conn, $_POST['seater']);
    $fees = mysqli_real_escape_string($conn, $_POST['fees']);

    $update = "UPDATE rooms SET 
                Room_No = '$room_no', 
                Seater = '$seater', 
                Fees = '$fees' 
              WHERE Room_ID = '$room_id'";

    if (mysqli_query($conn, $update)) {
      $message = "Room updated successfully!";
      // Refresh the room data
      $query = mysqli_query($conn, "SELECT * FROM rooms WHERE Room_ID = '$room_id'");
      $room = mysqli_fetch_assoc($query);
    } else {
      $message = "Error updating room: " . mysqli_error($conn);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Room</title>
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
      background-color: #007bff;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
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
  <h2>Edit Room</h2>

  <?php if ($message): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>

  <?php if ($room): ?>
  <form method="POST" action="">
    <label for="room_no">Room Number</label>
    <input type="text" name="room_no" id="room_no" value="<?= $room['Room_No'] ?>" required>

    <label for="seater">Seater</label>
    <input type="number" name="seater" id="seater" value="<?= $room['Seater'] ?>" min="1" required>

    <label for="fees">Fees</label>
    <input type="number" name="fees" id="fees" value="<?= $room['Fees'] ?>" min="0" required>

    <button type="submit">Update Room</button>
  </form>
  <?php endif; ?>

  <a href="manage_rooms.php" class="back-link">← Back to Manage Rooms</a>
</body>
</html>
