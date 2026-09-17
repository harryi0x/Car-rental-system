<?php include 'database_connection.php'; requireCustomer();

$user = getCurrentUser();

// Handle Booking via AJAX/Modal
if(isset($_POST['book_car'])) {
    $car_id = mysqli_real_escape_string($conn, $_POST['car_id']);
    $car_name = mysqli_real_escape_string($conn, $_POST['car_name']);
    $car_model = mysqli_real_escape_string($conn, $_POST['car_model']);
    $car_color = mysqli_real_escape_string($conn, $_POST['car_color']);
    $days = mysqli_real_escape_string($conn, $_POST['days']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $month = mysqli_real_escape_string($conn, $_POST['month']);
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $res_date = "$year-$month-$day";
    
    // Get customer by user email
    $customer_query = mysqli_query($conn, "SELECT customer_id FROM customer WHERE email_id='{$user['email']}'");
    $customer = mysqli_fetch_assoc($customer_query);
    
    if(!$customer) {
        echo "<script>alert('Please complete your customer profile first. Contact admin.');</script>";
        exit();
    }
    
    $customer_id = $customer['customer_id'];
    
    // Get car price
    $car_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price_per_day FROM car WHERE car_id='$car_id'"));
    $total = $car_data['price_per_day'] * $days;
    
    $query = "INSERT INTO reservation (car_id, car_name, car_model, car_color, customer_id, reservation_date, days, total_amount, status) 
              VALUES ('$car_id', '$car_name', '$car_model', '$car_color', '$customer_id', '$res_date', '$days', '$total', 'Pending')";
    
    if(mysqli_query($conn, $query)) {
        mysqli_query($conn, "UPDATE car SET car_status='Booked' WHERE car_id='$car_id'");
        echo "<script>alert('Car booked successfully! Total: PKR " . number_format($total) . "'); window.location='customer_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Car Rental Pakistan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f0f2f5; }
        
        /* Navbar */
        .navbar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 100;
            flex-wrap: wrap;
        }
        .logo { font-size: 24px; font-weight: 700; color: #2563eb; }
        .user-info { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .user-name { color: #333; font-weight: 500; }
        .logout-btn { background: #ef4444; color: white; padding: 8px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: 0.3s; }
        .logout-btn:hover { background: #dc2626; }
        
        /* Container */
        .container { max-width: 1400px; margin: 0 auto; padding: 20px 30px; }
        
        /* Banner */
        .banner {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 40px 30px;
            border-radius: 16px;
            margin-bottom: 30px;
        }
        .banner h1 { font-size: 28px; margin-bottom: 10px; }
        .banner p { opacity: 0.9; }
        
        /* Menu Cards */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .menu-card {
            background: white;
            padding: 25px 20px;
            text-align: center;
            border-radius: 12px;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-color: #2563eb; }
        .menu-card h3 { font-size: 18px; margin-bottom: 8px; color: #1f2937; }
        .menu-card p { font-size: 13px; color: #6b7280; }
        
        /* Section Title */
        .section-title { font-size: 22px; font-weight: 600; margin-bottom: 20px; color: #1f2937; }
        
        /* Cars Grid */
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .car-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: 0.3s;
            border: 1px solid #e5e7eb;
        }
        .car-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .car-card img { width: 100%; height: 200px; object-fit: cover; }
        .car-info { padding: 20px; }
        .car-info h3 { font-size: 18px; font-weight: 600; margin-bottom: 5px; color: #1f2937; }
        .car-detail { font-size: 13px; color: #6b7280; margin-bottom: 10px; }
        .car-price { font-size: 22px; font-weight: 700; color: #2563eb; margin-bottom: 15px; }
        .car-price span { font-size: 13px; font-weight: normal; color: #6b7280; }
        .book-btn {
            width: 100%;
            padding: 10px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }
        .book-btn:hover { background: #1d4ed8; }
        
        /* Table */
        .table-container { overflow-x: auto; margin-bottom: 30px; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        th { background: #f9fafb; padding: 12px 15px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; }
        td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.approved { background: #d1fae5; color: #065f46; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            width: 500px;
            max-width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlide 0.3s ease;
        }
        @keyframes modalSlide {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 { font-size: 20px; color: #1f2937; }
        .close-modal { font-size: 28px; cursor: pointer; color: #9ca3af; transition: 0.3s; }
        .close-modal:hover { color: #ef4444; }
        .modal-body { padding: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #374151; font-size: 14px; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #2563eb; }
        .form-group input[readonly] { background: #f3f4f6; cursor: not-allowed; }
        .date-group { display: flex; gap: 10px; }
        .date-group select { flex: 1; }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        .submit-btn:hover { background: #1d4ed8; }
        .cancel-btn {
            width: 100%;
            padding: 12px;
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        .cancel-btn:hover { background: #e5e7eb; }
        .no-data { padding: 40px; text-align: center; color: #6b7280; background: white; border-radius: 12px; }
        
        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 15px; text-align: center; }
            .menu-grid { grid-template-columns: 1fr; }
            .container { padding: 15px; }
            .banner h1 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Car Rental Pakistan</div>
        <div class="user-info">
            <span class="user-name">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="banner">
            <h1>Find Your Perfect Ride</h1>
            <p>Choose from our wide range of luxury and economy cars across Pakistan</p>
        </div>
        
        <div class="menu-grid">
            
            <a href="my_reservations.php" class="menu-card">
                <h3>My Reservations</h3>
                <p>View your bookings</p>
            </a>
        </div>
        
        <h2 class="section-title">Available Cars for Rent</h2>
        <div class="cars-grid">
            <?php
            $cars = mysqli_query($conn, "SELECT * FROM car WHERE car_status='Available' ORDER BY car_id DESC");
            if(mysqli_num_rows($cars) > 0):
                while($car = mysqli_fetch_assoc($cars)):
            ?>
            <div class="car-card">
                <img src="<?php echo htmlspecialchars($car['image_url'] ?? 'https://via.placeholder.com/400x250?text=Car'); ?>" 
                     alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                <div class="car-info">
                    <h3><?php echo htmlspecialchars($car['car_name']); ?></h3>
                    <div class="car-detail"><?php echo htmlspecialchars($car['car_model']); ?> | <?php echo htmlspecialchars($car['car_color']); ?></div>
                    <div class="car-price">PKR <?php echo number_format($car['price_per_day']); ?> <span>/ day</span></div>
                    <button class="book-btn" onclick="openBookingModal(
                        <?php echo $car['car_id']; ?>, 
                        '<?php echo htmlspecialchars($car['car_name']); ?>', 
                        '<?php echo htmlspecialchars($car['car_model']); ?>', 
                        '<?php echo htmlspecialchars($car['car_color']); ?>', 
                        <?php echo $car['price_per_day']; ?>
                    )">Book Now</button>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
            <div class="no-data">No cars available at the moment.</div>
            <?php endif; ?>
        </div>
        
        <h2 class="section-title">My Recent Bookings</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>ID</th><th>Car</th><th>Model</th><th>Date</th><th>Days</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php
                    // Get customer by user email
                    $cust_query = mysqli_query($conn, "SELECT customer_id FROM customer WHERE email_id='{$user['email']}'");
                    $customer_data = mysqli_fetch_assoc($cust_query);
                    
                    if($customer_data) {
                        $customer_id = $customer_data['customer_id'];
                        $my_reservations = mysqli_query($conn, "SELECT * FROM reservation WHERE customer_id='$customer_id' ORDER BY reservation_id DESC LIMIT 5");
                        if(mysqli_num_rows($my_reservations) > 0):
                            while($res = mysqli_fetch_assoc($my_reservations)):
                    ?>
                    <tr>
                        <td><?php echo $res['reservation_id']; ?></td>
                        <td><?php echo $res['car_name']; ?></td>
                        <td><?php echo $res['car_model']; ?></td>
                        <td><?php echo $res['reservation_date']; ?></td>
                        <td><?php echo $res['days']; ?></td>
                        <td>PKR <?php echo number_format($res['total_amount']); ?></td>
                        <td><span class="status-badge <?php echo strtolower($res['status']); ?>"><?php echo $res['status']; ?></span></td>
                    </tr>
                    <?php 
                            endwhile;
                        else:
                    ?>
                    <tr><td colspan="7" class="no-data">No bookings yet.</td></tr>
                    <?php endif; 
                    } else { ?>
                    <tr><td colspan="7" class="no-data">Please complete your profile. Contact admin.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Book a Car</h2>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" id="bookingForm">
                    <input type="hidden" name="car_id" id="car_id">
                    <input type="hidden" name="book_car" value="1">
                    <div class="form-group">
                        <label>Car Name</label>
                        <input type="text" name="car_name" id="car_name" readonly>
                    </div>
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="car_model" id="car_model" readonly>
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="text" name="car_color" id="car_color" readonly>
                    </div>
                    <div class="form-group">
                        <label>Reservation Date</label>
                        <div class="date-group">
                            <select name="year" required>
                                <option>2025</option><option>2026</option><option>2027</option>
                            </select>
                            <select name="month" required>
                                <?php for($m=1;$m<=12;$m++): ?>
                                <option value="<?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?></option>
                                <?php endfor; ?>
                            </select>
                            <select name="day" required>
                                <?php for($d=1;$d<=31;$d++): ?>
                                <option value="<?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Number of Days</label>
                        <input type="number" name="days" id="days" min="1" required onkeyup="updateTotal()" onchange="updateTotal()">
                    </div>
                    <div class="form-group">
                        <label>Total Amount (PKR)</label>
                        <input type="text" id="total_amount" readonly style="background:#f3f4f6; font-weight:bold; color:#2563eb;">
                    </div>
                    <button type="submit" class="submit-btn">Confirm Booking</button>
                    <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        let currentPrice = 0;
        
        function openBookingModal(id, name, model, color, price) {
            currentPrice = price;
            document.getElementById('car_id').value = id;
            document.getElementById('car_name').value = name;
            document.getElementById('car_model').value = model;
            document.getElementById('car_color').value = color;
            document.getElementById('days').value = '';
            document.getElementById('total_amount').value = '';
            document.getElementById('bookingModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }
        
        function updateTotal() {
            let days = document.getElementById('days').value;
            if(days > 0) {
                let total = days * currentPrice;
                document.getElementById('total_amount').value = 'PKR ' + total.toLocaleString();
            } else {
                document.getElementById('total_amount').value = '';
            }
        }
        
        window.onclick = function(event) {
            let modal = document.getElementById('bookingModal');
            if(event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>