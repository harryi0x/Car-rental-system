<?php
include 'database_connection.php';
requireAdmin();

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_query($conn, "DELETE FROM car WHERE car_id='$id'");
}
header("Location: main_form.php");
exit();
?>