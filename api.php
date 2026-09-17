<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include 'database_connection.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($request_method) {
    case 'POST':
        if($action == 'login') {
            login($conn);
        } elseif($action == 'register') {
            register($conn);
        } elseif($action == 'book_car') {
            bookCar($conn);
        }
        break;
        
    case 'GET':
        if($action == 'get_cars') {
            getCars($conn);
        } elseif($action == 'my_bookings') {
            getMyBookings($conn);
        } elseif($action == 'check_status') {
            checkStatus($conn);
        }
        break;
        
    case 'PUT':
        if($action == 'update_status') {
            updateStatus($conn);
        }
        break;
        
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        break;
}

// Login Function
function login($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = md5($data['password']);
    
    $query = "SELECT id, username, full_name, email, phone, role FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 'success', 'user' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
    }
}

// Register Function
function register($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = md5($data['password']);
    $full_name = mysqli_real_escape_string($conn, $data['full_name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    
    // Check if username exists
    $check = mysqli_query($conn, "SELECT id FROM admin WHERE username='$username'");
    if(mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already exists']);
        return;
    }
    
    // Check if email exists
    $check_email = mysqli_query($conn, "SELECT id FROM admin WHERE email='$email'");
    if(mysqli_num_rows($check_email) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already registered']);
        return;
    }
    
    $query = "INSERT INTO admin (username, password, role, full_name, email, phone, address) 
              VALUES ('$username', '$password', 'customer', '$full_name', '$email', '$phone', '$address')";
    
    if(mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn);
        
        // Also add to customer table
        $customer_query = "INSERT INTO customer (customer_name, email_id, cnic, phone_no, address) 
                          VALUES ('$full_name', '$email', '12345-6789012-3', '$phone', '$address')";
        mysqli_query($conn, $customer_query);
        
        echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
    }
}

// Get All Available Cars
function getCars($conn) {
    $query = "SELECT car_id, car_name, car_model, car_color, price_per_day, image_url, car_status FROM car WHERE car_status='Available' ORDER BY car_id DESC";
    $result = mysqli_query($conn, $query);
    
    $cars = [];
    while($row = mysqli_fetch_assoc($result)) {
        $cars[] = $row;
    }
    echo json_encode(['status' => 'success', 'cars' => $cars]);
}

// Book a Car
function bookCar($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $user_id = $data['user_id'];
    $car_id = $data['car_id'];
    $days = $data['days'];
    $reservation_date = $data['reservation_date'];
    
    // Get user email
    $user_query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email, full_name FROM admin WHERE id='$user_id'"));
    $user_email = $user_query['email'];
    $user_name = $user_query['full_name'];
    
    // Get customer id by email
    $customer_query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT customer_id FROM customer WHERE email_id='$user_email'"));
    
    if(!$customer_query) {
        echo json_encode(['status' => 'error', 'message' => 'Customer profile not found']);
        return;
    }
    
    $customer_id = $customer_query['customer_id'];
    
    // Get car details
    $car_query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT car_name, car_model, car_color, price_per_day FROM car WHERE car_id='$car_id'"));
    $total = $car_query['price_per_day'] * $days;
    
    $insert = "INSERT INTO reservation (car_id, car_name, car_model, car_color, customer_id, reservation_date, days, total_amount, status) 
               VALUES ('$car_id', '{$car_query['car_name']}', '{$car_query['car_model']}', '{$car_query['car_color']}', 
                       '$customer_id', '$reservation_date', '$days', '$total', 'Pending')";
    
    if(mysqli_query($conn, $insert)) {
        mysqli_query($conn, "UPDATE car SET car_status='Booked' WHERE car_id='$car_id'");
        echo json_encode(['status' => 'success', 'message' => 'Car booked successfully', 'total' => $total]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Booking failed']);
    }
}

// Get My Bookings
function getMyBookings($conn) {
    $user_id = $_GET['user_id'];
    
    // Get customer by user email
    $user_query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM admin WHERE id='$user_id'"));
    $customer_query = mysqli_fetch_assoc(mysqli_query($conn, "SELECT customer_id FROM customer WHERE email_id='{$user_query['email']}'"));
    
    if(!$customer_query) {
        echo json_encode(['status' => 'success', 'bookings' => []]);
        return;
    }
    
    $customer_id = $customer_query['customer_id'];
    $query = "SELECT reservation_id, car_name, car_model, car_color, reservation_date, days, total_amount, status 
              FROM reservation WHERE customer_id='$customer_id' ORDER BY reservation_id DESC";
    $result = mysqli_query($conn, $query);
    
    $bookings = [];
    while($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
    echo json_encode(['status' => 'success', 'bookings' => $bookings]);
}

// Check Status (Specific booking)
function checkStatus($conn) {
    $booking_id = $_GET['booking_id'];
    $query = "SELECT status, car_name, reservation_date, days, total_amount FROM reservation WHERE reservation_id='$booking_id'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 'success', 'booking' => $booking]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Booking not found']);
    }
}

// Update Status (Admin only - for app)
function updateStatus($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $booking_id = $data['booking_id'];
    $status = $data['status'];
    
    mysqli_query($conn, "UPDATE reservation SET status='$status' WHERE reservation_id='$booking_id'");
    
    if($status == 'Rejected') {
        $res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT car_id FROM reservation WHERE reservation_id='$booking_id'"));
        mysqli_query($conn, "UPDATE car SET car_status='Available' WHERE car_id='{$res['car_id']}'");
    }
    
    echo json_encode(['status' => 'success', 'message' => 'Status updated']);
}
?>