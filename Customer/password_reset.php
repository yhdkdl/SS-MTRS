<?php
// Assuming this is a POST request with the email address
$email = $_POST['email'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'th_ctms');

// Check if email exists in customers table
$query = "SELECT * FROM customers WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No account associated with this email.");
}

// Generate a secure token
$reset_token = bin2hex(random_bytes(32));

// Save token in the password_reset table
$query = "INSERT INTO password_reset (email, reset_token) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $email, $reset_token);

if ($stmt->execute()) {
    // Send reset link to the customer's email
    $reset_link = "http://yourwebsite.com/reset_password.php?token=$reset_token";
    mail($email, "Password Reset", "Click the link to reset your password: $reset_link");

    echo "A password reset link has been sent to your email.";
} else {
    echo "Failed to process password reset request.";
}

$stmt->close();
$conn->close();
?>
