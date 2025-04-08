<?php
include '../includes/db_connect.php';

// Fetch state and city data for dropdowns
$states_result = $conn->query("SELECT * FROM State");
$cities_result = $conn->query("SELECT * FROM City");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $guardian_name = $_POST['guardian_name'];
    $guardian_contact = $_POST['guardian_contact'];
    $address = $_POST['address'];
    $city_id = $_POST['city'];
    $state_id = $_POST['state'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $sql = "INSERT INTO Student (First_Name, Middle_Name, Last_Name, Gender, DOB, Email, Phone, Course, Guardian_Name, Guardian_Contact, Address, City_ID, State_ID, Password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssiss", $first_name, $middle_name, $last_name, $gender, $dob, $email, $phone, $course, $guardian_name, $guardian_contact, $address, $city_id, $state_id, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap & Custom CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
            border-radius: 1.5rem;
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        label {
            font-weight: 500;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, .25);
            transition: all 0.2s;
        }
        .form-control {
            transition: all 0.3s ease;
        }
        button {
            padding: 10px 30px;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .form-section {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg p-5">
        <h2 class="text-center mb-4">Student Registration</h2>
        <form method="POST">
            <div class="row form-section">
                <div class="col-md-4 mb-3">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>

            <div class="form-section">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>

            <div class="form-section">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
            </div>

            <div class="row form-section">
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
            </div>

            <div class="form-section">
                <label>Course</label>
                <select name="course" class="form-control" required>
                    <option value="">Select Course</option>
                    <option>B.Tech Computer Engineering</option>
                    <option>B.Tech Information Technology</option>
                    <option>B.Tech Artificial Intelligence and Data Science</option>
                    <option>B.Tech Artificial Intelligence and Machine Learning</option>
                    <option>B.Tech Electronics and Telecommunication</option>
                    <option>B.Tech Mechanical Engineering</option>
                    <option>B.Tech Civil Engineering</option>
                    <option>B.Tech Electrical Engineering</option>
                    <option>B.Tech Instrumentation Engineering</option>
                </select>
            </div>

            <div class="row form-section">
                <div class="col-md-6 mb-3">
                    <label>Guardian Name</label>
                    <input type="text" name="guardian_name" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Guardian Contact</label>
                    <input type="text" name="guardian_contact" class="form-control">
                </div>
            </div>

            <div class="form-section">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>

            <div class="row form-section">
                <div class="col-md-6 mb-3">
                    <label>State</label>
                    <select name="state" id="stateDropdown" class="form-control" required>
                        <option value="">Select State</option>
                        <?php while($row = $states_result->fetch_assoc()): ?>
                            <option value="<?= $row['State_ID'] ?>"><?= $row['State_Name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>City</label>
                    <select name="city" id="cityDropdown" class="form-control" required>
                        <option value="">Select City</option>
                        <?php while($row = $cities_result->fetch_assoc()): ?>
                            <option value="<?= $row['City_ID'] ?>" data-state="<?= $row['State_ID'] ?>">
                                <?= $row['City_Name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Register</button>
                <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>
</div>

<!-- JS for State-City Filtering -->
<script>
    const stateDropdown = document.getElementById('stateDropdown');
    const cityDropdown = document.getElementById('cityDropdown');

    stateDropdown.addEventListener('change', () => {
        const selectedState = stateDropdown.value;
        for (let option of cityDropdown.options) {
            option.style.display = option.getAttribute('data-state') === selectedState ? 'block' : 'none';
        }
        cityDropdown.value = ""; // reset city selection
    });
</script>

</body>
</html>
