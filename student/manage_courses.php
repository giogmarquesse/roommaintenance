<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);
    $has_major = isset($_POST['has_major']);
    $major_name = $has_major ? trim($_POST['major_name']) : null;

    if (!empty($course_name)) {
        $stmt = $pdo->prepare("INSERT INTO courses (course_name) VALUES (?)");
        $stmt->execute([$course_name]);
        $course_id = $pdo->lastInsertId();

        if ($has_major && !empty($major_name)) {
            $stmt = $pdo->prepare("INSERT INTO majors (major_name, course_id) VALUES (?, ?)");
            $stmt->execute([$major_name, $course_id]);
        }
    }

    header("Location: manage_courses.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $pdo->prepare("DELETE FROM majors WHERE course_id = ?")->execute([$delete_id]);
    $pdo->prepare("DELETE FROM courses WHERE course_id = ?")->execute([$delete_id]);
    header("Location: manage_courses.php");
    exit;
}

// Fetch data
$sql = "SELECT c.course_id, c.course_name, m.major_name
        FROM courses c
        LEFT JOIN majors m ON c.course_id = m.course_id
        ORDER BY c.course_name ASC";
$courses = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function toggleMajorField() {
        const checkbox = document.getElementById('has_major');
        const majorField = document.getElementById('major_field');
        majorField.style.display = checkbox.checked ? 'block' : 'none';
    }
    </script>
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Manage Courses & Majors</h2>
    <a href="index.php" class="btn btn-secondary mb-4">Back to Dashboard</a>

    <!-- Add Form -->
    <form method="POST" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="text" name="course_name" class="form-control" placeholder="Course Name" required>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="has_major" id="has_major" onclick="toggleMajorField()">
                    <label class="form-check-label" for="has_major">Has Major?</label>
                </div>
            </div>
            <div class="col-md-4" id="major_field" style="display: none;">
                <input type="text" name="major_name" class="form-control" placeholder="Major Name">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Add</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Course Name</th>
                <th>Major</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= htmlspecialchars($course['course_name']) ?></td>
                <td><?= $course['major_name'] ? htmlspecialchars($course['major_name']) : '<em>No major</em>' ?></td>
                <td>
                    <a href="?delete=<?= $course['course_id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this course and its major?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
