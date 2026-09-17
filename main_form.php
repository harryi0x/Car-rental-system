<?php include 'database_connection.php'; requireAdmin();

$user = getCurrentUser();
$search = isset($_GET['search']) ? $_GET['search'] : '';

if($search != '') {
    $car_query = "SELECT * FROM car WHERE car_name LIKE '%$search%' OR car_model LIKE '%$search%'";
} else {
    $car_query = "SELECT * FROM car ORDER BY car_id DESC";
}
$car_result = mysqli_query($conn, $car_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Car Rental Pakistan</title>
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
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 30px;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        
        .stat-card h3 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            font-size: 32px;
            font-weight: 700;
            color: #2563eb;
        }
        
        /* Actions */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
        }
        
        /* Search */
        .search-section {
            margin-bottom: 30px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .search-form input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .btn-search {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .btn-reset {
            background: #6b7280;
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 8px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }
        
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
            border: 1px solid #e5e7eb;
            transition: 0.3s;
        }
        
        .car-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .car-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .car-info {
            padding: 15px;
        }
        
        .car-info h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .car-detail {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .car-price {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .status.available {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status.booked {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .car-actions {
            display: flex;
            gap: 10px;
        }
        
        .edit-btn {
            background: #f59e0b;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
        }
        
        .delete-btn {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
        }
        
        /* Table */
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        th {
            background: #f9fafb;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.approved { background: #d1fae5; color: #065f46; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }
        
        .approve-btn, .reject-btn {
            padding: 4px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            margin: 0 2px;
        }
        
        .approve-btn { background: #10b981; color: white; }
        .reject-btn { background: #ef4444; color: white; }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Admin Panel - Car Rental Pakistan</div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Cars</h3>
                <p><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM car")); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reservation")); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Customers</h3>
                <p><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customer")); ?></p>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="car_detail.php" class="btn-primary">Add New Car</a>
            <a href="customer_detail.php" class="btn-primary">Add Customer</a>
            <a href="reservation_form.php" class="btn-primary">Make Reservation</a>
            <a href="check_status.php" class="btn-primary">All Reservations</a>
        </div>
        
        <div class="search-section">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by Car Name or Model" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn-search">Search</button>
                <?php if($search): ?>
                    <a href="main_form.php" class="btn-reset">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        
        <h2 class="section-title">All Cars</h2>
        <div class="cars-grid">
            <?php while($car = mysqli_fetch_assoc($car_result)): ?>
            <div class="car-card">
                <img src="<?php echo htmlspecialchars($car['image_url'] ?? 'https://via.placeholder.com/400x250?text=Car'); ?>" alt="<?php echo $car['car_name']; ?>">
                <div class="car-info">
                    <h3><?php echo htmlspecialchars($car['car_name']); ?></h3>
                    <div class="car-detail"><?php echo $car['car_model']; ?> | <?php echo $car['car_color']; ?></div>
                    <div class="car-price">PKR <?php echo number_format($car['price_per_day']); ?>/day</div>
                    <div class="status <?php echo strtolower($car['car_status']); ?>"><?php echo $car['car_status']; ?></div>
                    <div class="car-actions">
                        <a href="edit_car.php?id=<?php echo $car['car_id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_car.php?id=<?php echo $car['car_id']; ?>" class="delete-btn" onclick="return confirm('Delete this car?')">Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <h2 class="section-title">Recent Reservations</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>ID</th><th>Car</th><th>Customer</th><th>Date</th><th>Days</th><th>Total</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php
                    $reservations = mysqli_query($conn, "SELECT r.*, c.customer_name FROM reservation r LEFT JOIN customer c ON r.customer_id = c.customer_id ORDER BY r.reservation_id DESC LIMIT 5");
                    while($res = mysqli_fetch_assoc($reservations)):
                    ?>
                    <tr>
                        <td><?php echo $res['reservation_id']; ?></td>
                        <td><?php echo $res['car_name']; ?></td>
                        <td><?php echo $res['customer_name'] ?? $res['customer_id']; ?></td>
                        <td><?php echo $res['reservation_date']; ?></td>
                        <td><?php echo $res['days']; ?></td>
                        <td>PKR <?php echo number_format($res['total_amount']); ?></td>
                        <td><span class="status-badge <?php echo strtolower($res['status']); ?>"><?php echo $res['status']; ?></span></td>
                        <td>
                            <?php if($res['status'] == 'Pending'): ?>
                                <a href="update_status.php?id=<?php echo $res['reservation_id']; ?>&status=Approved" class="approve-btn">Approve</a>
                                <a href="update_status.php?id=<?php echo $res['reservation_id']; ?>&status=Rejected" class="reject-btn">Reject</a>
                            <?php else: ?>
                                --
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>