<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "car_rental_system";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function isCustomer() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'customer';
}

function requireLogin() {
    if(!isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if(!isAdmin()) {
        header("Location: customer_dashboard.php");
        exit();
    }
}

function requireCustomer() {
    requireLogin();
    if(!isCustomer()) {
        header("Location: main_form.php");
        exit();
    }
}

function getCurrentUser() {
    global $conn;
    if(isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE id='$user_id'");
        return mysqli_fetch_assoc($query);
    }
    return null;
}

// Get customer by user id (admin table se)
function getCustomerByUserId($user_id) {
    global $conn;
    $query = mysqli_query($conn, "SELECT c.* FROM customer c JOIN admin a ON c.email_id = a.email WHERE a.id='$user_id'");
    return mysqli_fetch_assoc($query);
}
?>