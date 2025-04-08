<?php
session_start();
include '../includes/db_connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM student WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['Password'])) {
            $_SESSION['student_id'] = $row['Student_ID'];
            $_SESSION['student_name'] = $row['First_Name'] . ' ' . $row['Last_Name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/images/background.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
            color: white;
            text-align: center;
            width: 350px;
        }
        .btn-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <?php if (!empty($error)) echo "<p class='text-danger'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-custom btn-lg w-100">Login</button>
        </form>
        <div class="mt-3 text-center">
            <p>Don't have an account? <a href="register.php" class="text-white">Create a new account</a></p>
        </div>
        <form action="../index.php" method="get">
    <button type="submit" class="btn btn-custom btn-lg w-100">Go to Home Page</button>
</form>

    </div>
</body>
</html>
