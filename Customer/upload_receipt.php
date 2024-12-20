<?php
session_start();
include 'connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $customer_id = $_SESSION['user']['id'];
    
    // Validate file upload
    if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading receipt. Please try again.");
    }

    $file = $_FILES['receipt'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        die("Invalid file type. Only JPG, PNG, and GIF files are allowed.");
    }

    // Generate a unique file name
    $file_name = 'receipt_' . $booking_id . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $upload_dir = 'uploads/';
    $upload_path = $upload_dir . $file_name;

    // Create upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move uploaded file to the designated directory
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        die("Failed to save the uploaded file. Please try again.");
    }

    // Update the bookings table with the receipt image
    $query = "UPDATE bookings SET receipt_img = ? WHERE id = ? AND customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $file_name, $booking_id, $customer_id);

    if ($stmt->execute()) {
        echo "Receipt uploaded successfully.";
    } else {
        echo "Error updating the database.";
    }

    // Redirect back to My Bookings page
    header("Location: my_booking.php");
    exit;
}
?>
