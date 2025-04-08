<?php
// admin/edit_student.php
include '../includes/db_connect.php';

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$student_id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM student WHERE Student_ID = $student_id");
$student = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    $stmt = $conn->prepare("UPDATE student SET First_Name=?, Last_Name=?, Gender=?, DOB=?, Email=?, Phone=?, Course=? WHERE Student_ID=?");
    $stmt->bind_param("sssssssi", $first_name, $last_name, $gender, $dob, $email, $phone, $course, $student_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Student</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      padding: 40px;
      margin: 0;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    form {
      max-width: 500px;
      margin: 0 auto;
      padding: 30px;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 10px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #555;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      width: 100%;
    }

    button:hover {
      background-color: #0056b3;
    }

    .dashboard-btn {
      display: inline-block;
      margin-bottom: 20px;
      background-color: #28a745;
      color: white;
      padding: 10px 15px;
      text-decoration: none;
      border-radius: 5px;
    }

    .dashboard-btn:hover {
      background-color: #218838;
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
  <h2>Edit Student Details</h2>
  <a href="../admin/dashboard.php" class="dashboard-btn">← Back to Dashboard</a>

  <form method="post">
    <label>First Name:</label><br>
    <input type="text" name="first_name" value="<?= $student['First_Name'] ?>" required><br><br>
    
    <label>Last Name:</label><br>
    <input type="text" name="last_name" value="<?= $student['Last_Name'] ?>" required><br><br>
    
    <label>Gender:</label><br>
    <select name="gender" required>
      <option value="Male" <?= $student['Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
      <option value="Female" <?= $student['Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
    </select><br><br>
    
    <label>DOB:</label><br>
    <input type="date" name="dob" value="<?= $student['DOB'] ?>" required><br><br>
    
    <label>Email:</label><br>
    <input type="email" name="email" value="<?= $student['Email'] ?>" required><br><br>
    
    <label>Phone:</label><br>
    <input type="text" name="phone" value="<?= $student['Phone'] ?>" required><br><br>
    
    <label>Course:</label><br>
    <input type="text" name="course" value="<?= $student['Course'] ?>" required><br><br>
    
    <button type="submit">Update</button>
  </form>
</body>
</html>
