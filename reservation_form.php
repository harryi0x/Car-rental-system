<?php include 'database_connection.php'; requireLogin();

$is_admin = isAdmin();
$selected_car_id = isset($_GET['car_id']) ? $_GET['car_id'] : '';

$cars_query = mysqli_query($conn, "SELECT * FROM car WHERE car_status='Available'");
$customers_query = mysqli_query($conn, "SELECT * FROM customer");

if(isset($_POST['reserve'])) {
    $car_id = mysqli_real_escape_string($conn, $_POST['car_id']);
    $car_name = mysqli_real_escape_string($conn, $_POST['car_name']);
    $car_model = mysqli_real_escape_string($conn, $_POST['car_model']);
    $car_color = mysqli_real_escape_string($conn, $_POST['car_color']);
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $month = mysqli_real_escape_string($conn, $_POST['month']);
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $days = mysqli_real_escape_string($conn, $_POST['days']);
    $res_date = "$year-$month-$day";
    
    $car_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price_per_day FROM car WHERE car_id='$car_id'"));
    $total = $car_data['price_per_day'] * $days;
    
    $query = "INSERT INTO reservation (car_id, car_name, car_model, car_color, customer_id, reservation_date, days, total_amount, status) 
              VALUES ('$car_id', '$car_name', '$car_model', '$car_color', '$customer_id', '$res_date', '$days', '$total', 'Pending')";
    
    if(mysqli_query($conn, $query)) {
        mysqli_query($conn, "UPDATE car SET car_status='Booked' WHERE car_id='$car_id'");
        echo "<script>alert('Car reserved! Total: PKR " . number_format($total) . "'); window.location='" . ($is_admin ? 'check_status.php' : 'my_reservations.php') . "';</script>";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation - Car Rental Pakistan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .form-container {
            background: white;
            border-radius: 24px;
            width: 600px;
            max-width: 100%;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .form-header p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 22px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
            background: white;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .form-group input[readonly] {
            background: #f3f4f6;
            cursor: not-allowed;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .date-group {
            display: flex;
            gap: 12px;
        }
        
        .date-group select {
            flex: 1;
            padding: 12px;
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            width: 100%;
            padding: 12px;
            background: #f3f4f6;
            color: #374151;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            display: inline-block;
            margin-top: 12px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-cancel:hover {
            background: #e5e7eb;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .button-group .btn-submit {
            flex: 2;
            margin-top: 0;
        }
        
        .button-group .btn-cancel {
            flex: 1;
            margin-top: 0;
        }
        
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }
        
        .info-note {
            background: #eff6ff;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            color: #1e40af;
            text-align: center;
            margin-top: 15px;
        }
        
        @media (max-width: 600px) {
            .form-container {
                padding: 25px;
            }
            .form-header h1 {
                font-size: 24px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .date-group {
                flex-direction: column;
                gap: 8px;
            }
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Book a Car</h1>
            <p>Fill the details to reserve your favorite car</p>
        </div>
        
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Select Car</label>
                <select name="car_id" required onchange="fillCarDetails(this)">
                    <option value="">-- Select a Car --</option>
                    <?php 
                    mysqli_data_seek($cars_query, 0);
                    while($car = mysqli_fetch_assoc($cars_query)): 
                        $selected = ($selected_car_id == $car['car_id']) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $car['car_id']; ?>" 
                        data-name="<?php echo htmlspecialchars($car['car_name']); ?>"
                        data-model="<?php echo htmlspecialchars($car['car_model']); ?>"
                        data-color="<?php echo htmlspecialchars($car['car_color']); ?>"
                        data-price="<?php echo $car['price_per_day']; ?>"
                        <?php echo $selected; ?>>
                        <?php echo $car['car_name'] . " - " . $car['car_model'] . " (PKR " . number_format($car['price_per_day']) . "/day)"; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Car Name</label>
                    <input type="text" name="car_name" id="car_name" readonly>
                </div>
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" name="car_model" id="car_model" readonly>
                </div>
            </div>
            
            <div class="form-group">
                <label>Color</label>
                <input type="text" name="car_color" id="car_color" readonly>
            </div>
            
            <div class="form-group">
                <label>Select Customer</label>
                <select name="customer_id" required>
                    <option value="">-- Select Customer --</option>
                    <?php 
                    mysqli_data_seek($customers_query, 0);
                    while($cust = mysqli_fetch_assoc($customers_query)): ?>
                    <option value="<?php echo $cust['customer_id']; ?>">
                        <?php echo $cust['customer_name'] . " - " . $cust['email_id']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Reservation Date</label>
                <div class="date-group">
                    <select name="year" required>
                        <option value="">Year</option>
                        <option>2025</option>
                        <option>2026</option>
                        <option>2027</option>
                    </select>
                    <select name="month" required>
                        <option value="">Month</option>
                        <?php for($m=1;$m<=12;$m++): ?>
                        <option value="<?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="day" required>
                        <option value="">Day</option>
                        <?php for($d=1;$d<=31;$d++): ?>
                        <option value="<?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Number of Days</label>
                <input type="number" name="days" id="days" min="1" placeholder="Enter number of days" required onkeyup="calculateTotal()" onchange="calculateTotal()">
            </div>
            
            <div class="form-group">
                <label>Total Amount (PKR)</label>
                <input type="text" id="total_display" readonly style="background:#f3f4f6; font-weight:700; color:#2563eb; font-size:18px;">
                <input type="hidden" name="total_amount" id="total_amount">
            </div>
            
            <div class="info-note">
                Note: Your booking will be pending until admin approval.
            </div>
            
            <div class="button-group">
                <button type="submit" name="reserve" class="btn-submit">Confirm Reservation</button>
                <a href="<?php echo $is_admin ? 'main_form.php' : 'customer_dashboard.php'; ?>" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
    
    <script>
        let currentPrice = 0;
        
        function fillCarDetails(select) {
            var opt = select.options[select.selectedIndex];
            document.getElementById('car_name').value = opt.dataset.name || '';
            document.getElementById('car_model').value = opt.dataset.model || '';
            document.getElementById('car_color').value = opt.dataset.color || '';
            currentPrice = parseFloat(opt.dataset.price) || 0;
            calculateTotal();
        }
        
        function calculateTotal() {
            let days = document.getElementById('days').value;
            if(days > 0 && currentPrice > 0) {
                let total = days * currentPrice;
                document.getElementById('total_display').value = 'PKR ' + total.toLocaleString();
                document.getElementById('total_amount').value = total;
            } else {
                document.getElementById('total_display').value = '';
                document.getElementById('total_amount').value = '';
            }
        }
        
        <?php if($selected_car_id): ?>
        window.onload = function() {
            var select = document.querySelector('select[name="car_id"]');
            if(select) {
                select.dispatchEvent(new Event('change'));
            }
        }
        <?php endif; ?>
    </script>
</body>
</html>