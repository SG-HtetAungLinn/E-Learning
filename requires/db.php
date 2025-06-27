<?php

$host = "localhost";
$username = "root";
$password = "password";

$mysqli = new mysqli($host, $username, $password);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
createDatabase($mysqli);
function createDatabase($mysqli)
{
    $sql = "CREATE DATABASE IF NOT EXISTS `learning` 
        DEFAULT CHARACTER SET utf8mb4
        COLLATE utf8mb4_general_ci";
    $mysqli->query($sql);
}
selectDatabase($mysqli);
function selectDatabase($mysqli)
{
    if ($mysqli->select_db("learning")) {
        return true;
    }
    return false;
}
createTable($mysqli);
function createTable($mysqli)
{
    // create user
    $user_sql = "CREATE TABLE IF NOT EXISTS `users`
                (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name  VARCHAR(100) NOT NULL,
                email VARCHAR(50) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                password VARCHAR(200) NOT NULL,
                role ENUM('admin', 'student'),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                ) ENGINE=InnoDB";
    $mysqli->query($user_sql);

    // create category
    $category_sql = "CREATE TABLE IF NOT EXISTS `categories`
                    (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name  VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                    ) ENGINE=InnoDB";
    $mysqli->query($category_sql);

    // create subject
    $subject_sql = "CREATE TABLE IF NOT EXISTS `subjects`
                    (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name  VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                    ) ENGINE=InnoDB";
    $mysqli->query($subject_sql);

    // create course
    $course_sql = "CREATE TABLE IF NOT EXISTS `courses`
                    (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name  VARCHAR(100) NOT NULL,
                    category_id INT UNSIGNED NOT NULL,
                    description TEXT NOT NULL,
                    duration VARCHAR(50) NOT NULL,
                    price VARCHAR(10),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB";
    $mysqli->query($course_sql);

    // course_subject
    $course_sub_sql = "CREATE TABLE IF NOT EXISTS `course_subject`
        (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        course_id INT UNSIGNED NOT NULL,
        subject_id INT UNSIGNED NOT NULL, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB";
    $mysqli->query($course_sub_sql);

    // lesson
    $lesson_sql = "CREATE TABLE IF NOT EXISTS `lessons`
                    (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    course_subject_id INT UNSIGNED NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    description VARCHAR(255) DEFAULT NULL,
                    link  VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (course_subject_id) REFERENCES course_subject(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB";
    $mysqli->query($lesson_sql);

    // course_student
    $course_std_sql = "CREATE TABLE IF NOT EXISTS `course_student`
                    (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    user_id INT UNSIGNED NOT NULL,
                    course_id INT UNSIGNED NOT NULL,
                    status BOOL NOT NULL DEFAULT FALSE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB";
    $mysqli->query($course_std_sql);

    // course_student
    $feedback_sql = "CREATE TABLE IF NOT EXISTS `feedback`
                    (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    text TEXT NOT NULL,
                    course_id INT UNSIGNED NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB";
    $mysqli->query($feedback_sql);
}
