<?php
include('../Database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Generate a password reset token
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in the database
        $updateQuery = "UPDATE customers SET reset_token = '$token', reset_token_expiry = '$expiry' WHERE email = '$email'";
        mysqli_query($conn, $updateQuery);

        // Send reset link (for demo, just display it)
        echo "Password reset link: http://localhost/SS-MTRS-main/customer/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click on the following link to reset your password: $reset_link";
        mail($email, $subject, $message); // Send the reset email
        
        echo "A password reset link has been sent to your email.";
    } else {
        echo "Email not found.";
    }
}
?>
<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Reset Password</button>
</form>
