<?php include 'database_connection.php'; requireAdmin();

if(isset($_POST['add_car'])) {
    $car_name = mysqli_real_escape_string($conn, $_POST['car_name']);
    $car_model = mysqli_real_escape_string($conn, $_POST['car_model']);
    $car_color = mysqli_real_escape_string($conn, $_POST['car_color']);
    $car_reg_no = mysqli_real_escape_string($conn, $_POST['car_reg_no']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    
    $query = "INSERT INTO car (car_name, car_model, car_color, car_registration_no, price_per_day, image_url, car_status) 
              VALUES ('$car_name', '$car_model', '$car_color', '$car_reg_no', '$price', '$image_url', 'Available')";
    
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Car added successfully'); window.location='main_form.php';</script>";
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
    <title>Add Car - Car Rental Pakistan</title>
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
        
        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .form-group input[readonly] {
            background: #f3f4f6;
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #2563eb;
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
            border-radius: 10px;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
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
            <h1>Add New Car</h1>
            <p>Enter car details to add to inventory</p>
        </div>
        
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Car Name</label>
                    <input type="text" name="car_name" placeholder="e.g., Toyota Camry" required>
                </div>
                <div class="form-group">
                    <label>Car Model</label>
                    <input type="text" name="car_model" placeholder="e.g., 2023" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Car Color</label>
                    <input type="text" name="car_color" placeholder="e.g., White, Black" required>
                </div>
                <div class="form-group">
                    <label>Registration Number</label>
                    <input type="text" name="car_reg_no" placeholder="e.g., ABC-1234" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Price per Day (PKR)</label>
                    <input type="number" step="0.01" name="price" placeholder="e.g., 15000" required>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" placeholder="https://example.com/car.jpg">
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" name="add_car" class="btn-submit">Add Car</button>
                <a href="main_form.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>