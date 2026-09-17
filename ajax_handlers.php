<?php
include 'database_connection.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if($action == 'add_car') {
    if(!isAdmin()) die('unauthorized');
    
    $car_name = mysqli_real_escape_string($conn, $_POST['car_name']);
    $car_model = mysqli_real_escape_string($conn, $_POST['car_model']);
    $car_color = mysqli_real_escape_string($conn, $_POST['car_color']);
    $car_reg_no = mysqli_real_escape_string($conn, $_POST['car_reg_no']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    
    $query = "INSERT INTO cars (car_name, car_model, car_color, car_registration_no, price_per_day, image_url, car_status) 
              VALUES ('$car_name', '$car_model', '$car_color', '$car_reg_no', '$price', '$image_url', 'Available')";
    
    if(mysqli_query($conn, $query)) echo 'success';
    else echo mysqli_error($conn);
}

elseif($action == 'delete_car') {
    if(!isAdmin()) die('unauthorized');
    $car_id = $_POST['car_id'];
    mysqli_query($conn, "DELETE FROM cars WHERE car_id='$car_id'");
    echo 'success';
}

elseif($action == 'book_car') {
    if(!isLoggedIn()) die('unauthorized');
    
    $car_id = $_POST['car_id'];
    $days = $_POST['days'];
    $reservation_date = $_POST['reservation_date'];
    $user_id = $_SESSION['user_id'];
    
    // Get car details
    $car = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cars WHERE car_id='$car_id'"));
    $total = $car['price_per_day'] * $days;
    
    $query = "INSERT INTO reservations (car_id, car_name, car_model, car_color, user_id, reservation_date, days, total_amount, status) 
              VALUES ('$car_id', '{$car['car_name']}', '{$car['car_model']}', '{$car['car_color']}', '$user_id', '$reservation_date', '$days', '$total', 'Pending')";
    
    if(mysqli_query($conn, $query)) {
        mysqli_query($conn, "UPDATE cars SET car_status='Booked' WHERE car_id='$car_id'");
        echo 'success';
    } else echo mysqli_error($conn);
}

elseif($action == 'update_status') {
    if(!isAdmin()) die('unauthorized');
    $reservation_id = $_POST['reservation_id'];
    $status = $_POST['status'];
    
    mysqli_query($conn, "UPDATE reservations SET status='$status' WHERE reservation_id='$reservation_id'");
    
    if($status == 'Rejected') {
        $res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT car_id FROM reservations WHERE reservation_id='$reservation_id'"));
        mysqli_query($conn, "UPDATE cars SET car_status='Available' WHERE car_id='{$res['car_id']}'");
    }
    echo 'success';
}

elseif($action == 'get_car') {
    $id = $_GET['id'];
    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cars WHERE car_id='$id'"));
    echo json_encode($result);
}
?>