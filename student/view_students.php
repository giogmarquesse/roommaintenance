<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get selected year level from URL parameter
$selected_year = isset($_GET['year_level']) ? $_GET['year_level'] : '';

// Fetch all year levels for the dropdown
$year_sql = "SELECT year_level_id, year_name FROM year_levels ORDER BY year_level_id";
$year_levels = $pdo->query($year_sql)->fetchAll();

// Base SQL query
$sql = "SELECT s.full_name, c.course_name, y.year_name
        FROM students s
        JOIN courses c ON s.course_id = c.course_id
        JOIN year_levels y ON s.year_level_id = y.year_level_id";

// Add WHERE clause if year level is selected
if (!empty($selected_year)) {
    $sql .= " WHERE y.year_level_id = :year_level";
}

$sql .= " ORDER BY s.student_id DESC";

// Prepare and execute query
$stmt = $pdo->prepare($sql);
if (!empty($selected_year)) {
    $stmt->bindParam(':year_level', $selected_year, PDO::PARAM_INT);
}
$stmt->execute();
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Students List</h2>
        
        <!-- Year Level Filter -->
        <div class="d-flex align-items-center gap-3">
            <label for="yearFilter" class="form-label mb-0 fw-semibold">Filter by Year:</label>
            <select id="yearFilter" class="form-select" style="width: auto;" onchange="filterByYear()">
                <option value="">All Years</option>
                <?php foreach ($year_levels as $year): ?>
                    <option value="<?= $year['year_level_id'] ?>" 
                            <?= ($selected_year == $year['year_level_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($year['year_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <a href="index.php" class="btn btn-secondary mb-4">Back to Dashboard</a>
    
    <!-- Display current filter status -->
    <?php if (!empty($selected_year)): ?>
        <?php
        // Get the selected year name for display
        $selected_year_name = '';
        foreach ($year_levels as $year) {
            if ($year['year_level_id'] == $selected_year) {
                $selected_year_name = $year['year_name'];
                break;
            }
        }
        ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>Showing students from: <strong><?= htmlspecialchars($selected_year_name) ?></strong></span>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-sm btn-outline-secondary">Clear Filter</a>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <?php if (count($students) > 0): ?>
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Year Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['full_name']) ?></td>
                            <td><?= htmlspecialchars($student['course_name']) ?></td>
                            <td><?= htmlspecialchars($student['year_name']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <h5>No students found</h5>
                    <p>
                        <?php if (!empty($selected_year)): ?>
                            No students found for the selected year level.
                        <?php else: ?>
                            No students have been added yet.
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Student count display -->
    <div class="mt-3 text-muted">
        <small>
            Showing <?= count($students) ?> student<?= count($students) != 1 ? 's' : '' ?>
            <?php if (!empty($selected_year)): ?>
                for <?= htmlspecialchars($selected_year_name) ?>
            <?php endif; ?>
        </small>
    </div>
</div>

<script>
function filterByYear() {
    const select = document.getElementById('yearFilter');
    const selectedValue = select.value;
    
    if (selectedValue === '') {
        // Redirect to page without year parameter
        window.location.href = '<?= $_SERVER['PHP_SELF'] ?>';
    } else {
        // Redirect to page with selected year parameter
        window.location.href = '<?= $_SERVER['PHP_SELF'] ?>?year_level=' + selectedValue;
    }
}
</script>
</body>
</html>