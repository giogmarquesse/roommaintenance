<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: manage_students.php");
    exit;
}

// Handle update
if (isset($_POST['update_student'])) {
    // Check if major is empty, if so set it as NULL
    $major_id = isset($_POST['major_id']) && $_POST['major_id'] !== '' ? $_POST['major_id'] : null;
    
    $stmt = $pdo->prepare("UPDATE students SET student_number = ?, full_name = ?, course_id = ?, major_id = ?, year_level_id = ? WHERE student_id = ?");
    $stmt->execute([
        $_POST['student_number'],
        $_POST['full_name'],
        $_POST['course_id'],
        $major_id, // Update major_id based on the selected course
        $_POST['year_level_id'],
        $_POST['student_id']
    ]);
    header("Location: manage_students.php");
    exit;
}

// Fetch students with course, major, year
$students = $pdo->query("
    SELECT s.student_id, s.student_number, s.full_name,
           c.course_name, c.course_id,
           m.major_name, m.major_id,
           y.year_name, y.year_level_id
    FROM students s
    JOIN courses c ON s.course_id = c.course_id
    LEFT JOIN majors m ON s.major_id = m.major_id
    JOIN year_levels y ON s.year_level_id = y.year_level_id
    ORDER BY s.student_id DESC
")->fetchAll();

// Fetch dropdown data
$courses = $pdo->query("SELECT * FROM courses")->fetchAll();
$majors = $pdo->query("SELECT * FROM majors")->fetchAll();
$year_levels = $pdo->query("SELECT * FROM year_levels")->fetchAll();

// If editing
$edit_student = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $edit_student = $stmt->fetch();
}

// Fetch majors for a specific course
function getMajorsForCourse($course_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM majors WHERE course_id = ?");
    $stmt->execute([$course_id]);
    return $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Manage Students</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search student...">
    </div>

    <?php if ($edit_student): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Edit Student</h5>
            <form method="POST">
                <input type="hidden" name="student_id" value="<?= $edit_student['student_id'] ?>">

                <div class="mb-2">
                    <label class="form-label">Student Number</label>
                    <input type="text" name="student_number" class="form-control" required value="<?= htmlspecialchars($edit_student['student_number']) ?>">
                </div>

                <div class="mb-2">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required value="<?= htmlspecialchars($edit_student['full_name']) ?>">
                </div>

                <div class="mb-2">
                    <label class="form-label">Course</label>
                    <select name="course_id" id="courseSelect" class="form-select" required>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['course_id'] ?>" <?= $edit_student['course_id'] == $course['course_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['course_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-2" id="majorDiv" style="display: <?= count(getMajorsForCourse($edit_student['course_id'])) > 0 ? 'block' : 'none' ?>;">
                    <label class="form-label">Major</label>
                    <select name="major_id" id="majorSelect" class="form-select">
                        <?php
                            $majorsForCourse = getMajorsForCourse($edit_student['course_id']);
                            foreach ($majorsForCourse as $major): ?>
                            <option value="<?= $major['major_id'] ?>" <?= $edit_student['major_id'] == $major['major_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($major['major_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Year Level</label>
                    <select name="year_level_id" class="form-select" required>
                        <?php foreach ($year_levels as $level): ?>
                            <option value="<?= $level['year_level_id'] ?>" <?= $edit_student['year_level_id'] == $level['year_level_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($level['year_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="update_student" class="btn btn-success">Save</button>
                <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Student Number</th>
                <th>Full Name</th>
                <th>Course</th>
                <th>Major</th>
                <th>Year Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['student_number']) ?></td>
                <td><?= htmlspecialchars($student['full_name']) ?></td>
                <td><?= htmlspecialchars($student['course_name']) ?></td>
                <td><?= htmlspecialchars($student['major_name'] ?? 'None') ?></td>
                <td><?= htmlspecialchars($student['year_name']) ?></td>
                <td>
                    <a href="?edit_id=<?= $student['student_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete_id=<?= $student['student_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(filter) ? '' : 'none';
    });
});

document.getElementById('courseSelect').addEventListener('change', function() {
    const courseId = this.value;
    const majorDiv = document.getElementById('majorDiv');
    const majorSelect = document.getElementById('majorSelect');

    // Hide major input field if course has no majors
    if (courseId === "") {
        majorDiv.style.display = 'none';
    } else {
        // Fetch majors for selected course
        fetchMajors(courseId);
        majorDiv.style.display = 'block';
    }
});

function fetchMajors(courseId) {
    const majorSelect = document.getElementById('majorSelect');
    fetch('get_majors.php?course_id=' + courseId)
        .then(response => response.json())
        .then(data => {
            majorSelect.innerHTML = '<option value="">Select Major</option>';
            data.forEach(major => {
                majorSelect.innerHTML += `<option value="${major.major_id}">${major.major_name}</option>`;
            });
        });
}
</script>

</body>
</html>
