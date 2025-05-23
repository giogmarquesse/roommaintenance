<?php
include('conn.php');

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Fetch majors for the selected course
    $majors = $pdo->prepare("SELECT * FROM majors WHERE course_id = ?");
    $majors->execute([$course_id]);
    $majors = $majors->fetchAll();

    // If there are majors, generate options
    if ($majors) {
        echo '<option value="">Select Major</option>';
        foreach ($majors as $major) {
            echo '<option value="' . $major['major_id'] . '">' . $major['major_name'] . '</option>';
        }
    }
}
?>
