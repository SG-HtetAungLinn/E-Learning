<?php
require "./requires/db.php";
require "./requires/common.php";
require "./requires/common_function.php";
header('Content-Type: application/json');
$id = $_POST['id'];
$sql = "SELECT 
            lessons.id AS lesson_id,
            lessons.name AS lesson_name,
            lessons.description AS lesson_description,
            lessons.link,
            courses.name AS course_name,
            course_subject.subject_id,
            subjects.name AS subject_name
        FROM lessons
            JOIN course_subject ON lessons.course_subject_id = course_subject.id
            JOIN courses ON course_subject.course_id = courses.id
            JOIN subjects ON course_subject.subject_id = subjects.id
        WHERE course_subject.course_id = '$id'
        ORDER BY lessons.id ASC";
$lesson_res = $mysqli->query($sql);
$lessons = array();
if ($lesson_res->num_rows > 0) {
    while ($row = $lesson_res->fetch_assoc()) {
        $lessons[] = $row;
    }
}

$groupedLessons = array();

foreach ($lessons as $lesson) {
    $subjectId = $lesson['subject_id']; // 2
    if (!isset($groupedLessons[$subjectId])) {
        $groupedLessons[$subjectId] = [
            'subject_id' => $lesson['subject_id'],
            'subject_name' => $lesson['subject_name'],
            'lessons' => []
        ];
    }
    $groupedLessons[$subjectId]['lessons'][] = [
        'lesson_id' => $lesson['lesson_id'],
        'lesson_name' => $lesson['lesson_name'],
        'lesson_description' => $lesson['lesson_description'],
        'link' => $lesson['link'],
        'course_name' => $lesson['course_name']
    ];
}

echo json_encode(array_values($groupedLessons));
