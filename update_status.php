<?php
include 'database_connection.php';
requireAdmin();

$id = mysqli_real_escape_string($conn, $_GET['id']);
$status = mysqli_real_escape_string($conn, $_GET['status']);

mysqli_query($conn, "UPDATE reservation SET status='$status' WHERE reservation_id='$id'");

if($status == 'Rejected') {
    $res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT car_id FROM reservation WHERE reservation_id='$id'"));
    mysqli_query($conn, "UPDATE car SET car_status='Available' WHERE car_id='{$res['car_id']}'");
} elseif($status == 'Approved') {
    $res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT car_id FROM reservation WHERE reservation_id='$id'"));
    mysqli_query($conn, "UPDATE car SET car_status='Booked' WHERE car_id='{$res['car_id']}'");
}

header("Location: check_status.php");
exit();
?>