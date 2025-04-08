<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Hostel Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
        }

        .navbar {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
        }

        .logout-btn {
            background: #e74c3c;
            padding: 10px 18px;
            border: none;
            color: white;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .container {
            padding: 40px;
        }

        .container h2 {
            font-size: 26px;
            color: #1e3c72;
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card i {
            font-size: 36px;
            color: #2a5298;
            margin-bottom: 15px;
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Welcome, <?= $_SESSION['admin_username'] ?></h1>
        <form method="post" action="logout.php">
            <button class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="container">
        <h2>Dashboard Overview</h2>
        <div class="cards">
            <a href="manage_students.php" class="card">
                <i class="fas fa-users"></i>
                <h3>Manage Students</h3>
                <p>View, approve, or delete student accounts</p>
            </a>

            <a href="manage_rooms.php" class="card">
                <i class="fas fa-door-open"></i>
                <h3>Manage Rooms</h3>
                <p>Monitor room availability and allocations</p>
            </a>

            <a href="manage_mess.php" class="card">
                <i class="fas fa-utensils"></i>
                <h3>Manage Mess</h3>
                <p>Track mess registrations and payments</p>
            </a>

            <a href="view_payments.php" class="card">
                <i class="fas fa-comment-dots"></i>
                <h3>Student Payments</h3>
                <p>Review and respond to feedback</p>
            </a>

            
        </div>
    </div>

</body>
</html>
