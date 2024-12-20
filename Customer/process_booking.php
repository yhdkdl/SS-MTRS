<?php
session_start();
include 'connect.php';
include 'aut.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Print received POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $showtime_id = $_POST['showtime_id'];
    $selected_seats = explode(',', $_POST['selected_seats']);
    $total_price = $_POST['total_price'];

    // Ensure valid inputs
    if (empty($selected_seats) || $total_price <= 0) {
        die("Invalid seat selection or total price.");
    }

    // Insert booking
    $query = "INSERT INTO bookings (customer_id, showtime_id, total_price, status) 
              VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $_SESSION['user']['id'], $showtime_id, $total_price);
    if (!$stmt->execute()) {
        die("Error inserting booking: " . $stmt->error);
    }
    $booking_id = $stmt->insert_id;

    // Link seats to booking and update seat status to 'temporarily booked' (status = 1)
    foreach ($selected_seats as $seat_id) {
        // Insert the selected seat into the booked_seats table
        $link_query = "INSERT INTO booked_seats (booking_id, seat_id) VALUES (?, ?)";
        $link_stmt = $conn->prepare($link_query);
        $link_stmt->bind_param("ii", $booking_id, $seat_id);
        if (!$link_stmt->execute()) {
            die("Error linking seat to booking: " . $link_stmt->error);
        }

        // Update the seat status to 'temporarily booked' (status = 1)
        $update_seat_query = "UPDATE seats SET status = 1 WHERE id = ?";
        $update_seat_stmt = $conn->prepare($update_seat_query);
        $update_seat_stmt->bind_param("i", $seat_id);
        if (!$update_seat_stmt->execute()) {
            die("Error updating seat status: " . $update_seat_stmt->error);
        }
    }

    // Redirect to bookings page
    header("Location: my_booking.php");
    exit;
}
?>
