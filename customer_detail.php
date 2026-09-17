<?php include 'database_connection.php'; requireAdmin();

if(isset($_POST['add_customer'])) {
    $name = mysqli_real_escape_string($conn, $_POST['cust_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $cnic = mysqli_real_escape_string($conn, $_POST['cnic']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $query = "INSERT INTO customer (customer_name, email_id, cnic, phone_no, address) 
              VALUES ('$name', '$email', '$cnic', '$phone', '$address')";
    
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Customer added successfully'); window.location='main_form.php';</script>";
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
    <title>Add Customer - Car Rental Pakistan</title>
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
            width: 550px;
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
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
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
            margin-top: 15px;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-cancel:hover {
            background: #e5e7eb;
        }
        
        @media (max-width: 480px) {
            .form-container {
                padding: 25px;
            }
            .form-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Add New Customer</h1>
            <p>Enter customer details to register</p>
        </div>
        
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="cust_name" placeholder="Full Name" required>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
            
            <div class="form-group">
                <label>CNIC Number</label>
                <input type="text" name="cnic" placeholder="12345-6789012-3" required>
            </div>
            
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="0300 1234567" required>
            </div>
            
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" rows="3" placeholder="Full Address"></textarea>
            </div>
            
            <button type="submit" name="add_customer" class="btn-submit">Add Customer</button>
            <a href="main_form.php" class="btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>