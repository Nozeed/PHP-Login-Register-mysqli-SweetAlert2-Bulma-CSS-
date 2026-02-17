<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=UTF-8');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function clean($data, $conn)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function checkLength($data, $min, $max)
{
    $len = strlen($data);
    return ($len >= $min && $len <= $max);
}

function checkLoggedIn()
{
    if (isset($_SESSION['user_id'])) {
        header("Location: dashboard.php");
        exit();
    }
}

function checkNotLoggedIn()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}
