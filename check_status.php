<?php include 'database_connection.php'; requireAdmin();

$reservations = mysqli_query($conn, "SELECT r.*, c.customer_name FROM reservation r LEFT JOIN customer c ON r.customer_id = c.customer_id ORDER BY r.reservation_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reservations - Admin</title>
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
        .btn-group { display: flex; gap: 15px; }
        .back-btn, .logout-btn {
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
            .navbar { flex-direction: column; gap: 15px; text-align: center; }
            .container { padding: 15px; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Admin Panel - Car Rental Pakistan</div>
        <div class="btn-group">
            <a href="main_form.php" class="back-btn">Back</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <h2 class="page-title">All Reservations</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Car</th><th>Model</th><th>Color</th>
                        <th>Customer</th><th>Date</th><th>Days</th>
                        <th>Total</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($reservations)): ?>
                    <tr>
                        <td><?php echo $row['reservation_id']; ?></td>
                        <td><?php echo $row['car_name']; ?></td>
                        <td><?php echo $row['car_model']; ?></td>
                        <td><?php echo $row['car_color']; ?></td>
                        <td><?php echo $row['customer_name'] ?? $row['customer_id']; ?></td>
                        <td><?php echo $row['reservation_date']; ?></td>
                        <td><?php echo $row['days']; ?></td>
                        <td>PKR <?php echo number_format($row['total_amount']); ?></td>
                        <td><span class="status-badge <?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if($row['status'] == 'Pending'): ?>
                                <a href="update_status.php?id=<?php echo $row['reservation_id']; ?>&status=Approved" class="approve-btn" onclick="return confirm('Approve this booking?')">Approve</a>
                                <a href="update_status.php?id=<?php echo $row['reservation_id']; ?>&status=Rejected" class="reject-btn" onclick="return confirm('Reject this booking?')">Reject</a>
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