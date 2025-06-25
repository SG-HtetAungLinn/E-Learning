<?php

if ($_SESSION['role'] !== 'student') {
    header("Location: $admin_base_url");
}
