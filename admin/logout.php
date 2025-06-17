<?php
require '../requires/common.php';
session_start();
if (session_destroy()) {
    $url = $admin_base_url . "login.php";
    header("Location: $url");
}
