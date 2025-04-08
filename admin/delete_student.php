<?php
// admin/delete_student.php
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);

    // Optional: delete related data first, like fees or registrations if needed

    $stmt = $conn->prepare("DELETE FROM student WHERE Student_ID = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: manage_students.php");
exit();
?>
