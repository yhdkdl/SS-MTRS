<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
require_once 'movies.php';


// Create Database connection
$db = new Database();
$conn = $db->getConnection();
 // Save receipt in the database
 $movie = new Movie($conn);

// Check if the user is logged in
if (!isset($_SESSION['user']['id'])) {
    die("User not logged in.");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['user']['id'];
    $booking_id = $_POST['booking_id'] ?? null;

    // Validate inputs
    if (!$booking_id) {
        die("Booking ID is missing.");
    }

    // Validate file upload
    if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading receipt. Please try again.");
    }

    $file = $_FILES['receipt'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        die("Invalid file type. Only JPG, PNG, and GIF files are allowed.");
    }

    // Generate a unique file name based on booking ID
    $file_name = 'receipt_' . $booking_id . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $upload_dir = 'uploads/';
    $upload_path = $upload_dir . $file_name;

    // Create upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move the uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        die("Failed to save the uploaded file. Please try again.");
    }

   

    try {
        $movie->saveReceipt($booking_id, $customer_id, $file_name);
        echo "Receipt uploaded successfully.";
        header("Location: my_booking.php");
        exit;
    } catch (Exception $e) {
        die("Error saving receipt: " . $e->getMessage());
    }
}