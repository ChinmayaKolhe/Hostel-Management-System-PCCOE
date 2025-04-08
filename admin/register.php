<?php
include('../includes/db_connect.php');

$message = '';
$success = false;

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM admin WHERE Email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO admin (Username, Email, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5 col-md-6">
    <div class="card shadow-lg rounded-4">
        <div class="card-body p-4">
            <h3 class="mb-4 text-center">Admin Registration</h3>

            <?php if ($message): ?>
                <div class="alert alert-danger"><?= $message ?></div>
            <?php endif; ?>

            <form id="registerForm" method="post">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control">
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                <div class="mt-3 text-center">
                    Already registered? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($success): ?>
<script>
    // Show processing spinner first
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // After delay, show success
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Successfully Registered!',
            text: 'Redirecting to login...',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = "login.php";
        });
    }, 1500);
</script>
<?php endif; ?>
</body>
</html>
