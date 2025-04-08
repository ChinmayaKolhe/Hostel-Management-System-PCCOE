<?php
include('../includes/db_connect.php');

// Handle Delete
if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $conn->query("DELETE FROM mess WHERE Mess_ID = '$delete_id'");
  header("Location: manage_mess.php");
  exit();
}

// Handle Edit Form Submission
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$edit_mess = null;

if ($edit_id) {
  $res = $conn->query("SELECT * FROM mess WHERE Mess_ID = '$edit_id'");
  $edit_mess = $res->fetch_assoc();
}

if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $name = $_POST['mess_name'];
  $opt = $_POST['options'];
  $status = $_POST['status'];
  $fee = $_POST['fee'];

  $sql = "UPDATE mess SET Name=?, Options=?, Food_Status=?, Mess_Fee=? WHERE Mess_ID=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssdi", $name, $opt, $status, $fee, $id);
  if ($stmt->execute()) {
    header("Location: manage_mess.php");
    exit();
  } else {
    echo "<p style='color:red;'>Error updating mess: " . $conn->error . "</p>";
  }
}

// Handle Add
if (isset($_POST['add'])) {
  $name = $_POST['mess_name'];
  $opt = $_POST['options'];
  $status = $_POST['status'];
  $fee = $_POST['fee'];

  $sql = "INSERT INTO mess (Name, Options, Food_Status, Mess_Fee) VALUES (?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssd", $name, $opt, $status, $fee);
  if ($stmt->execute()) {
    echo "<p style='color:green;'>Mess Registered Successfully.</p>";
  } else {
    echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Mess</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 20px;
      background: #f7f7f7;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }
    th {
      background: #3498db;
      color: #fff;
    }
    td {
      color: #2c3e50;
    }
    .btn {
      padding: 5px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }
    .btn-edit {
      background-color: #f39c12;
      color: white;
    }
    .btn-delete {
      background-color: #e74c3c;
      color: white;
    }
    .btn-add {
      background-color: #2ecc71;
      color: white;
      margin-bottom: 15px;
    }
    form input, form select {
      padding: 8px;
      margin: 5px 10px;
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
  <h2><?= $edit_mess ? 'Edit Mess' : 'Manage Mess' ?></h2>
  <a href="../admin/dashboard.php" class="dashboard-btn">← Back to Dashboard</a>
  <form method="POST">
    <?php if ($edit_mess): ?>
      <input type="hidden" name="id" value="<?= $edit_mess['Mess_ID'] ?>">
    <?php endif; ?>
    <input type="text" name="mess_name" placeholder="Mess Name" required value="<?= $edit_mess['Name'] ?? '' ?>">
    <select name="options" required>
      <option value="Veg" <?= isset($edit_mess) && $edit_mess['Options'] == 'Veg' ? 'selected' : '' ?>>Veg</option>
      <option value="Non-Veg" <?= isset($edit_mess) && $edit_mess['Options'] == 'Non-Veg' ? 'selected' : '' ?>>Non-Veg</option>
      <option value="Both" <?= isset($edit_mess) && $edit_mess['Options'] == 'Both' ? 'selected' : '' ?>>Both</option>
    </select>
    <select name="status" required>
      <option value="Active" <?= isset($edit_mess) && $edit_mess['Food_Status'] == 'Active' ? 'selected' : '' ?>>Active</option>
      <option value="Inactive" <?= isset($edit_mess) && $edit_mess['Food_Status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>
    <input type="number" step="0.01" name="fee" placeholder="Mess Fee" required value="<?= $edit_mess['Mess_Fee'] ?? '' ?>">
    <button type="submit" name="<?= $edit_mess ? 'update' : 'add' ?>" class="btn btn-add">
      <?= $edit_mess ? 'Update Mess' : 'Register Mess' ?>
    </button>
  </form>

  <table>
    <tr>
      <th>Mess ID</th>
      <th>Name</th>
      <th>Options</th>
      <th>Status</th>
      <th>Fee (Rs)</th>
      <th>Actions</th>
    </tr>
    <?php
    $query = "SELECT * FROM mess";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()):
    ?>
      <tr>
        <td><?= $row['Mess_ID'] ?></td>
        <td><?= $row['Name'] ?></td>
        <td><?= $row['Options'] ?></td>
        <td><?= $row['Food_Status'] ?></td>
        <td><?= $row['Mess_Fee'] ?></td>
        <td>
          <a href="manage_mess.php?edit=<?= $row['Mess_ID'] ?>" class="btn btn-edit">Edit</a>
          <a href="manage_mess.php?delete=<?= $row['Mess_ID'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
