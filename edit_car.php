<?php include 'database_connection.php'; requireAdmin();

$id = mysqli_real_escape_string($conn, $_GET['id']);
$car_result = mysqli_query($conn, "SELECT * FROM car WHERE car_id='$id'");
$car = mysqli_fetch_assoc($car_result);

if(!$car) {
    header("Location: main_form.php");
    exit();
}

if(isset($_POST['update'])) {
    $car_name = mysqli_real_escape_string($conn, $_POST['car_name']);
    $car_model = mysqli_real_escape_string($conn, $_POST['car_model']);
    $car_color = mysqli_real_escape_string($conn, $_POST['car_color']);
    $car_reg_no = mysqli_real_escape_string($conn, $_POST['car_reg_no']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    
    $query = "UPDATE car SET car_name='$car_name', car_model='$car_model', car_color='$car_color', 
              car_registration_no='$car_reg_no', price_per_day='$price', car_status='$status', image_url='$image_url' 
              WHERE car_id='$id'";
    mysqli_query($conn, $query);
    header("Location: main_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car - Car Rental Pakistan</title>
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
            border-radius: 20px;
            width: 600px;
            max-width: 100%;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
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
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #f59e0b;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background: #d97706;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            width: 100%;
            padding: 12px;
            background: #f3f4f6;
            color: #374151;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
            transition: all 0.3s;
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
        
        @media (max-width: 600px) {
            .form-container {
                padding: 25px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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
            <h1>Edit Car</h1>
            <p>Update car information</p>
        </div>
        
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Car Name</label>
                    <input type="text" name="car_name" value="<?php echo htmlspecialchars($car['car_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Car Model</label>
                    <input type="text" name="car_model" value="<?php echo htmlspecialchars($car['car_model']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Car Color</label>
                    <input type="text" name="car_color" value="<?php echo htmlspecialchars($car['car_color']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Registration Number</label>
                    <input type="text" name="car_reg_no" value="<?php echo htmlspecialchars($car['car_registration_no']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Price per Day (PKR)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $car['price_per_day']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" value="<?php echo htmlspecialchars($car['image_url']); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option <?php echo $car['car_status']=='Available'?'selected':''; ?>>Available</option>
                    <option <?php echo $car['car_status']=='Booked'?'selected':''; ?>>Booked</option>
                </select>
            </div>
            
            <div class="button-group">
                <button type="submit" name="update" class="btn-submit">Update Car</button>
                <a href="main_form.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>