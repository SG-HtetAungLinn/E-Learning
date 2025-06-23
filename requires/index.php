<?php
require "db.php";
require "common_function.php";
$category_data = [
    ['name' => 'PHP Course'],
    ['name' => 'Java Course'],
    ['name' => 'JavaScript Course'],
    ['name' => 'Node JS Course'],
    ['name' => 'Python Course'],
];
foreach ($category_data as $data) {
    insertData('categories', $mysqli, $data);
}

$subject_data = [
    ['name' => 'HTML'],
    ['name' => 'CSS'],
    ['name' => 'JavaScript'],
    ['name' => 'JQuery'],
    ['name' => 'Bootstrap'],
    ['name' => 'PHP'],
    ['name' => 'Java'],
    ['name' => 'Python'],
];
foreach ($subject_data as $data) {
    insertData('subjects', $mysqli, $data);
}
$user_data = [
    [
        'name'      => 'Admin',
        'email'     => 'admin@gmail.com',
        'phone'     => '0912345678',
        'password'  => md5('password'),
        'role'      => 'admin'
    ],
    [
        'name'      => 'User',
        'email'     => 'user@gmail.com',
        'phone'     => '0912345678',
        'password'  => md5('password'),
        'role'      => 'user'
    ],
];
foreach ($user_data as $data) {
    insertData('users', $mysqli, $data);
}
