<?php
include 'aut.php';
include 'connect.php';
$total_price = $_GET['total_price'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Booking Confirmed</h1>
    <p>Your total price is: $<?= htmlspecialchars($total_price) ?></p>
    <p>Thank you for booking with us!</p>
    <a href="index.php">Go to Dashboard</a>
</body>
</html>
