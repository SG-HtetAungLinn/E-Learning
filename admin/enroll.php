<?php
require '../requires/common.php';
require "../requires/common_function.php";
require "../requires/db.php";
header('Content-Type: application/json');

$id = isset($_POST['id']) ? $_POST['id'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($id && isset($status)) {
    $data = [
        'status' => $status == 0 ? true : false
    ];
    $where = [
        'id' => $id
    ];
    $res = updateData('course_student', $mysqli, $data, $where);
    if ($res) {
        echo json_encode([
            'success' => true,
            'status' => $status == 0 ? 1 : 0,
            'message' => "Status Update Success"
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Status Update Fail"
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => "Some Param is require"
    ]);
}
