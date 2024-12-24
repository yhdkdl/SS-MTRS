<?php
session_start();

// Redirect to signin.php if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit;
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the Movie class and initialize it
require_once 'movies.php';
$db = new Database();
$conn = $db->getConnection();
$movie = new Movie($conn);

// Get the logged-in user's ID
$customer_id = $_SESSION['user']['id'];

// Fetch user details
$user = $movie->getUserDetails($customer_id);

// Fetch the bookings for the logged-in user
$bookings = $movie->getUserBookings($customer_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="../Customer/styles/my.css">
</head>
<body>
    <a href="index.php" class="logo">
        <i class='bx bxs-movie'></i>Movies
    </a>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
        <li><a href="index.php" class="home-active"> &lt;= Back Home</a></li>
    </ul>
    <h1>My Bookings</h1>

    <?php if ($bookings->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Movie</th>
                    <th>Name</th>
                    <th>booking number</th>
                    <th>Showtime</th>
                    <th>Seats</th>
                    <th>Total Price</th>
                    <th>Booking Status</th>
                    <th>Receipt</th>
                    <th>Ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['movie_title']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['booking_id']) ?></td>
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

                        <td>
                            <?php if ($row['ticket'] && strtolower($row['status']) === 'confirmed' && strtolower($row['payment_status']) === 'paid'): ?>
                                <a href="<?= htmlspecialchars($row['ticket']) ?>" target="_blank">View</a>
                                <a href="<?= htmlspecialchars($row['ticket']) ?>" download>Download</a>
                            <?php else: ?>
                                <span>Not available</span>
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
