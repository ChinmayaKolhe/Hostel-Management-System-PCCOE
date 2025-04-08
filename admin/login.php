<?php
session_start();
include('../includes/db_connect.php');

$loginStatus = ''; // success, error
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['Password'])) {
            $_SESSION['admin_id'] = $admin['Admin_ID']; 

            $_SESSION['admin_username'] = $admin['Username'];
            $loginStatus = 'success';
        } else {
            $loginStatus = 'error';
            $message = 'Invalid password!';
        }
    } else {
        $loginStatus = 'error';
        $message = 'No account found with that email!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3E8EDE, #79C1F8);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 360px;
            text-align: center;
            animation: fadeIn 1.2s ease;
            transition: all 0.3s ease-in-out;
        }

        .login-container:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: none;
            border-radius: 8px;
            background-color: #f0f0f0;
            transition: all 0.2s ease-in-out;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            background-color: #e6f0ff;
            outline: none;
            box-shadow: 0 0 0 2px #3E8EDE;
        }

        input[type="submit"] {
            background-color: #3E8EDE;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2c6fbf;
        }

        p {
            font-size: 14px;
            margin-top: 15px;
        }

        a {
            color: #3E8EDE;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #2c6fbf;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .go-home-form {
        max-width: 400px;
        margin: 30px auto;
    }

    .go-home-form button {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
        padding: 15px 25px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: background 0.3s ease, transform 0.2s;
    }

    .go-home-form button:hover {
        background: linear-gradient(135deg, #0056b3, #003f7f);
        transform: translateY(-2px);
    }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" id="loginForm">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        <form action="../index.php" method="get" class="go-home-form">
    <button type="submit">Go to Home Page</button>
</form>
    </div>

    <script>
        const loginStatus = "<?= $loginStatus ?>";
        const message = "<?= $message ?>";

        if (loginStatus === "success") {
            Swal.fire({
                title: 'Logging in...',
                text: 'Redirecting to dashboard...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                window.location.href = "dashboard.php";
            }, 1500);
        } else if (loginStatus === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: message
            });
        }
    </script>
</body>
</html>
