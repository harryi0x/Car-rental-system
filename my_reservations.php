<?php include 'database_connection.php'; requireCustomer();

$user = getCurrentUser();

// Get customer by email
$cust_query = mysqli_query($conn, "SELECT customer_id FROM customer WHERE email_id='{$user['email']}'");
$customer = mysqli_fetch_assoc($cust_query);

if($customer) {
    $customer_id = $customer['customer_id'];
    $reservations = mysqli_query($conn, "SELECT * FROM reservation WHERE customer_id='$customer_id' ORDER BY reservation_id DESC");
} else {
    $reservations = mysqli_query($conn, "SELECT * FROM reservation WHERE 1=0");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Car Rental Pakistan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f0f2f5; }
        .navbar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            flex-wrap: wrap;
        }
        .logo { font-size: 24px; font-weight: 700; color: #2563eb; }
        .user-info { display: flex; align-items: center; gap: 20px; }
        .logout-btn, .back-btn {
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
        }
        .back-btn { background: #6b7280; color: white; }
        .logout-btn { background: #ef4444; color: white; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px 30px; }
        .page-title { font-size: 24px; font-weight: 600; margin-bottom: 20px; color: #1f2937; }
        .table-container { overflow-x: auto; }
        table { width: 100%; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
        th { background: #f9fafb; padding: 12px 15px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb; }
        td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.approved { background: #d1fae5; color: #065f46; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }
        .no-data { text-align: center; padding: 40px; color: #6b7280; }
        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 15px; text-align: center; }
            .container { padding: 15px; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Car Rental Pakistan</div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
            <a href="customer_dashboard.php" class="back-btn">Back</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <h2 class="page-title">My Reservations</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>ID</th><th>Car</th><th>Model</th><th>Color</th><th>Date</th><th>Days</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($reservations) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($reservations)): ?>
                        <tr>
                            <td><?php echo $row['reservation_id']; ?></td>
                            <td><?php echo $row['car_name']; ?></td>
                            <td><?php echo $row['car_model']; ?></td>
                            <td><?php echo $row['car_color']; ?></td>
                            <td><?php echo $row['reservation_date']; ?></td>
                            <td><?php echo $row['days']; ?></td>
                            <td>PKR <?php echo number_format($row['total_amount']); ?></td>
                            <td><span class="status-badge <?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="no-data">No reservations found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>