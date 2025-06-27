<?php
require "./requires/db.php";
require "./requires/common.php";
require "./requires/common_function.php";
header('Content-Type: application/json');

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
if ($user_id && $course_id) {
    $data = [
        'user_id'       => $user_id,
        'course_id'     => $course_id
    ];
    $res = insertData('course_student', $mysqli, $data);
    if ($res) {
        echo json_encode([
            'success' => true,
            'message' => "Course Enroll Success."
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Course Enroll Fail."
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => "Parameter is require."
    ]);
}
