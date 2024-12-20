<?php

include 'connect.php';
include 'aut.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the logged-in user's ID
$customer_id = $_SESSION['user']['id'];

// Fetch user details
$user_query = "SELECT name, email FROM customers WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $customer_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Fetch the bookings for the logged-in user
$query = "
    SELECT 
        bookings.id AS booking_id, 
        bookings.total_price, 
        bookings.status, 
        bookings.receipt_img, 
        GROUP_CONCAT(seats.seat_number SEPARATOR ', ') AS selected_seats, 
        showtime.start_time AS showtime, 
        movies.title AS movie_title,  -- changed from name to title
        customers.name AS customer_name, 
        customers.email AS customer_email 
    FROM bookings
    JOIN booked_seats ON bookings.id = booked_seats.booking_id
    JOIN seats ON booked_seats.seat_id = seats.id
    JOIN showtime ON bookings.showtime_id = showtime.id
    JOIN movies ON showtime.movie_id = movies.id
    JOIN customers ON bookings.customer_id = customers.id
    WHERE bookings.customer_id = ?
    GROUP BY bookings.id
";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-pending {
            color: orange;
        }
        .status-completed {
            color: green;
        }
        .status-cancelled {
            color: red;
        }
        .upload-form {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>My Bookings</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Movie</th>
                    <th>Name</th>
                    <th>Email</th>
                     <th>Showtime</th>
                    <th>Seats</th>
                    <th>Total Price</th>
                    <th>Booking Status</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['movie_title']) ?></td>
                        <td></strong> <?= htmlspecialchars($user['name']) ?></td>
                        <td></strong> <?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($row['showtime']) ?></td>
                        <td><?= htmlspecialchars($row['selected_seats']) ?></td>
                        <td>$<?= htmlspecialchars($row['total_price']) ?></td>
                        <td class="status-<?= strtolower($row['status']) ?>">
                            <?= ucfirst($row['status']) ?>
                        </td>
  <td>
    <?php if (isset($row['receipt_img']) && $row['receipt_img']): ?>
        <?php 
        $receipt_path = 'uploads/' . $row['receipt_img'];
        if (file_exists($receipt_path)): ?>
            <a href="<?= htmlspecialchars($receipt_path) ?>" target="_blank">
                <img src="<?= htmlspecialchars($receipt_path) ?>" alt="Receipt Image" style="width: 100px; height: auto;">
            </a>
        <?php else: ?>
            <span>Receipt image not available.</span>
        <?php endif; ?>
    <?php else: ?>
        <!-- Upload Receipt Form -->
        <form 
            method="POST" 
            action="upload_receipt.php" 
            enctype="multipart/form-data" 
            class="upload-form">
            <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
            <input type="file" name="receipt" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
    <?php endif; ?>
</td>



                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no bookings yet.</p>
    <?php endif; ?>
</body>
</html>
