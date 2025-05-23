<?php
include('conn.php');

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $stmt = $pdo->prepare("SELECT * FROM majors WHERE course_id = ?");
    $stmt->execute([$course_id]);
    $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($majors);
}
