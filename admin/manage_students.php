<?php
// admin/manage_students.php
include '../includes/db_connect.php';


$result = mysqli_query($conn, "SELECT s.*, c.City_Name, st.State_Name FROM student s LEFT JOIN city c ON s.City_ID = c.City_ID LEFT JOIN state st ON s.State_ID = st.State_ID");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Students</title>
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
      margin-bottom: 20px;
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
      background-color: #007bff;
      color: #fff;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    a {
      text-decoration: none;
      color: #007bff;
    }
    a:hover {
      text-decoration: underline;
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
  <h2>All Registered Students</h2>
  <a href="../admin/dashboard.php" class="dashboard-btn">← Back to Dashboard</a>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Gender</th>
      <th>DOB</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Course</th>
      <th>City</th>
      <th>State</th>
      <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?= $row['Student_ID'] ?></td>
      <td><?= $row['First_Name'].' '.$row['Last_Name'] ?></td>
      <td><?= $row['Gender'] ?></td>
      <td><?= $row['DOB'] ?></td>
      <td><?= $row['Email'] ?></td>
      <td><?= $row['Phone'] ?></td>
      <td><?= $row['Course'] ?></td>
      <td><?= $row['City_Name'] ?></td>
      <td><?= $row['State_Name'] ?></td>
      <td><a href="edit_student.php?id=<?= $row['Student_ID'] ?>">Edit</a> | <a href="delete_student.php?id=<?= $row['Student_ID'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
    </tr>
    <?php } ?>
  </table>
</body>
</html>