<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('assets/images/background.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 1.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .container {
            background: rgba(8, 8, 8, 0.7);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            animation: slideIn 1s ease-in-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .btn-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.05);
        }
        .logo {
            width: 120px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="assets/images/logo.png" alt="Hostel Logo" class="logo">
        <h1 class="mb-4">Hostel Management System</h1>
        <p>Manage hostel rooms, students, fees, and more efficiently.</p>
        <div class="mt-4">
            <a href="admin/login.php" class="btn btn-custom btn-lg me-3">Admin Login</a>
            <a href="student/login.php" class="btn btn-custom btn-lg">Student Login</a>
        </div>
    </div>
</body>
</html>
