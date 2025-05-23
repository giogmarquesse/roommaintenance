<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Insert student
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_number = $_POST['student_number'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $course_id = $_POST['course_id'];
    $year_level_id = $_POST['year_level_id'];
    $major_id = !empty($_POST['major_id']) ? $_POST['major_id'] : null;  // Handle optional major
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    $status = "Active"; // Default
    $current_time = date('Y-m-d H:i:s'); // current timestamp

    $sql = "INSERT INTO students 
    (student_number, full_name, age, gender, course_id, year_level_id, major_id, contact_number, address, status, date_created, updated_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $student_number,
    $full_name,
    $age,
    $gender,
    $course_id,
    $year_level_id,
    !empty($major_id) ? $major_id : null,
    $contact_number,
    $address,
    $status,
    $current_time,
    $current_time
]);
}

// Fetch courses and year levels
$courses = $pdo->query("SELECT * FROM courses")->fetchAll();
$year_levels = $pdo->query("SELECT * FROM year_levels")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#major-container').hide();

            $('select[name="course_id"]').change(function () {
                var course_id = $(this).val();

                if (course_id) {
                    $.ajax({
                        url: 'fetch_majors.php',
                        type: 'GET',
                        data: { course_id: course_id },
                        success: function (data) {
                            if (data.trim() === '') {
                                $('#major-container').hide();
                            } else {
                                $('#major-container').show();
                                $('select[name="major_id"]').html(data);
                            }
                        }
                    });
                } else {
                    $('#major-container').hide();
                }
            });
        });
    </script>
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Register New Student</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Student Number</label>
            <input type="text" name="student_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control" required min="1">
        </div>
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Course</label>
            <select name="course_id" class="form-select" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Year Level</label>
            <select name="year_level_id" class="form-select" required>
                <option value="">Select Year Level</option>
                <?php foreach ($year_levels as $level): ?>
                    <option value="<?= $level['year_level_id'] ?>"><?= $level['year_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3" id="major-container">
            <label class="form-label">Major</label>
            <select name="major_id" class="form-select">
                <option value="">Select Major</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Register Student</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
