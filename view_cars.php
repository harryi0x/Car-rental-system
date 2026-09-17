<?php include 'database_connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cars - Car Rental Pakistan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
        }
        
        .navbar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            flex-wrap: wrap;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
        }
        
        .nav-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        
        .btn-back {
            background: #6b7280;
            color: white;
        }
        
        .btn-login {
            background: #2563eb;
            color: white;
        }
        
        .btn-signup {
            background: #10b981;
            color: white;
        }
        
        .btn-logout {
            background: #ef4444;
            color: white;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 30px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #1f2937;
        }
        
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }
        
        .car-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .car-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        
        .car-info {
            padding: 20px;
        }
        
        .car-info h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #1f2937;
        }
        
        .car-detail {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .car-price {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
            margin: 15px 0;
        }
        
        .car-price span {
            font-size: 14px;
            font-weight: normal;
            color: #6b7280;
        }
        
        .car-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .status-available {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-booked {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .book-link {
            display: block;
            text-align: center;
            padding: 12px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .book-link:hover {
            background: #1d4ed8;
        }
        
        .login-message {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 16px;
            margin-top: 30px;
        }
        
        .no-cars {
            background: white;
            padding: 50px;
            text-align: center;
            border-radius: 16px;
            color: #6b7280;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .container {
                padding: 15px;
            }
            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Car Rental Pakistan</div>
        <div class="nav-links">
            <?php if(isLoggedIn()): ?>
                <?php if(isAdmin()): ?>
                    <a href="main_form.php" class="btn-back">Admin Panel</a>
                <?php else: ?>
                    <a href="customer_dashboard.php" class="btn-back">Dashboard</a>
                <?php endif; ?>
                <a href="logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="index.php" class="btn-login">Login</a>
                <a href="signup.php" class="btn-signup">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="container">
        <h1 class="page-title">Available Cars</h1>
        
        <div class="cars-grid">
            <?php
            $cars = mysqli_query($conn, "SELECT * FROM car ORDER BY car_id DESC");
            if(mysqli_num_rows($cars) > 0):
                while($car = mysqli_fetch_assoc($cars)):
            ?>
            <div class="car-card">
                <img src="<?php echo htmlspecialchars($car['image_url'] ?? 'https://via.placeholder.com/400x250?text=Car'); ?>" 
                     alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                <div class="car-info">
                    <h3><?php echo htmlspecialchars($car['car_name']); ?></h3>
                    <div class="car-detail"><?php echo $car['car_model']; ?> | <?php echo $car['car_color']; ?></div>
                    <div class="car-detail">Reg: <?php echo $car['car_registration_no']; ?></div>
                    <div class="car-price">PKR <?php echo number_format($car['price_per_day']); ?> <span>/ day</span></div>
                    <div class="car-status <?php echo $car['car_status'] == 'Available' ? 'status-available' : 'status-booked'; ?>">
                        <?php echo $car['car_status']; ?>
                    </div>
                    <?php if(isLoggedIn() && isCustomer() && $car['car_status'] == 'Available'): ?>
                        <a href="reservation_form.php?car_id=<?php echo $car['car_id']; ?>" class="book-link">Book Now</a>
                    <?php elseif(!isLoggedIn()): ?>
                        <a href="index.php" class="book-link">Login to Book</a>
                    <?php elseif($car['car_status'] == 'Booked'): ?>
                        <div class="book-link" style="background:#9ca3af; cursor:not-allowed;">Not Available</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
            <div class="no-cars">No cars available at the moment.</div>
            <?php endif; ?>
        </div>
        
        <?php if(!isLoggedIn()): ?>
        <div class="login-message">
            <p>Want to book a car? <a href="index.php" style="color:#2563eb;">Login</a> or <a href="signup.php" style="color:#2563eb;">Sign Up</a> to get started!</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>