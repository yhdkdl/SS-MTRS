<?php
include '../Database/connection.php';
session_start();

if (!isset($_SESSION['customer_email'])) {
    echo "You must be logged in to submit feedback.";
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_email = $_SESSION['customer_email'];
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    $query = "INSERT INTO feedback (customer_email, feedback_text) VALUES ('$customer_email', '$feedback')";
    
    if (mysqli_query($conn, $query)) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
