<?php
include('../Database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $query = "SELECT * FROM customers WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $customer = mysqli_fetch_assoc($result);
    } else {
        echo "Invalid or expired token.";
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $updateQuery = "UPDATE customers SET password = '$hashedPassword', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
    mysqli_query($conn, $updateQuery);

    echo "Password updated successfully.";
    header("Location: signin.php");
    exit();
}
?>
<form method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="password" placeholder="Enter new password" required>
    <button type="submit">Update Password</button>
</form>
