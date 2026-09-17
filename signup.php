<?php include 'database_connection.php';

if(isLoggedIn()) {
    if(isAdmin()) header("Location: main_form.php");
    else header("Location: customer_dashboard.php");
    exit();
}

if(isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $check = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
    if(mysqli_num_rows($check) > 0) {
        $error = "Username already exists";
    } else {
        $query = "INSERT INTO admin (username, password, role, full_name, email, phone, address) 
                  VALUES ('$username', '$password', 'customer', '$full_name', '$email', '$phone', '$address')";
        if(mysqli_query($conn, $query)) {
            echo "<script>alert('Signup successful! Please login.'); window.location='index.php';</script>";
        } else {
            $error = "Signup failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Car Rental Pakistan</title>
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
        
        .signup-box {
            background: white;
            border-radius: 20px;
            width: 500px;
            max-width: 100%;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .signup-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .signup-header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .signup-header p {
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
        .form-group textarea,
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
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .btn-signup {
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
        
        .btn-signup:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
        
        .login-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .signup-box {
                padding: 25px;
            }
            .signup-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-box">
        <div class="signup-header">
            <h1>Create Account</h1>
            <p>Join as a Customer</p>
        </div>
        
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter email address">
            </div>
            
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required placeholder="Enter phone number">
            </div>
            
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" rows="2" placeholder="Enter your address"></textarea>
            </div>
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Choose a username">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Choose a password">
            </div>
            
            <button type="submit" name="signup" class="btn-signup">Sign Up</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="index.php">Login Here</a>
        </div>
    </div>
</body>
</html>