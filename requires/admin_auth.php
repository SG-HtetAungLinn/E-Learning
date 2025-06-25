<?php

if ($_SESSION['role'] !== 'admin' && $auth) {
    header("Location: $base_url");
}
