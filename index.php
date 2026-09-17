<?php include 'database_connection.php';

if(isLoggedIn()) {
    if(isAdmin()) header("Location: main_form.php");
    else header("Location: customer_dashboard.php");
    exit();
}

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        
        if($user['role'] == 'admin') header("Location: main_form.php");
        else header("Location: customer_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Car Rental Pakistan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .login-box h1 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .login-box p {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }
        .input-group input:focus {
            outline: none;
            border-color: #2563eb;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-login:hover { background: #1d4ed8; }
        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }
        .signup-link a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Car Rental Pakistan</h1>
        <p>Login to your account</p>
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter username">
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter password">
            </div>
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
        <div class="signup-link">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>