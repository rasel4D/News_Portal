<?php
session_start();

// Check if user is not logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Check if user role is valid
if(!in_array($_SESSION['role'], ['admin', 'subadmin'])) {
    header("Location: ../login.php");
    exit();
}
?> 