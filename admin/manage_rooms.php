<?php
session_start();
include '../includes/db_connect.php';

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM rooms WHERE Room_ID = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_rooms.php');
    exit();
  }
  

$result = mysqli_query($conn, "SELECT * FROM rooms");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Rooms</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f4f8;
      margin: 0;
      padding: 20px;
    }
    h2 {
      text-align: center;
      margin-bottom: 10px;
    }
    .add-room-btn {
      display: block;
      width: fit-content;
      margin: 0 auto 20px auto;
      background-color: #007bff;
      color: white;
      padding: 10px 15px;
      border-radius: 5px;
      text-align: center;
      text-decoration: none;
      font-weight: bold;
    }
    .add-room-btn:hover {
      background-color: #0056b3;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }
    th {
      background-color: #28a745;
      color: #fff;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .btn {
      padding: 5px 10px;
      border-radius: 4px;
      border: none;
      color: white;
      background-color: #28a745;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-danger {
      background-color: #dc3545;
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
  <h2>All Hostel Rooms</h2>
  <a href="../admin/dashboard.php" class="dashboard-btn">← Back to Dashboard</a>
  <a href="add_room.php" class="add-room-btn">+ Add New Room</a>

  <table>
    <tr>
      <th>Room ID</th>
      <th>Room No</th>
      <th>Seater</th>
      <th>Fees</th>
      <th>Posting Date</th>
      <th>Allocated Students</th>
      <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { 
      $room_id = $row['Room_ID'];
      $allocated_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM room_registration WHERE Room_ID = '$room_id'");

      $allocated = mysqli_fetch_assoc($allocated_query)['total'];
    ?>
    <tr>
      <td><?= $room_id ?></td>
      <td><?= $row['Room_No'] ?></td>
      <td><?= $row['Seater'] ?></td>
      <td>₹<?= $row['Fees'] ?></td>
      <td><?= $row['Posting_Date'] ?></td>
      <td><?= $allocated ?> student(s)</td>
      <td>
        <a class="btn" href="edit_room.php?id=<?= $room_id ?>">Edit</a>
        <a class="btn btn-danger" href="manage_rooms.php?delete=<?= $room_id ?>" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
      </td>
    </tr>
    <?php } ?>
  </table>
</body>
</html>
