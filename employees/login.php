<?php
session_start();
require_once "./includes/Database.php";
require_once './includes/User.php';

$error = "";

// Instantiate the Database and User classes
$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = $_POST['emp_id'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate and sanitize inputs
    $emp_id = $user->sanitize($emp_id);

    // Check password format
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
        $error = "Password must be at least 8 characters long, and include at least one uppercase and one lowercase letter.";
    } else {
        // Attempt authentication
        $authenticatedUser = $user->authenticate($emp_id, $password);

        if ($authenticatedUser) {
            // Start a secure session and redirect
            $user->startSession($authenticatedUser);

            if ($_SESSION['user_role'] === 'Admin') {
                header('Location:./Admin/Aheader.php');
            } elseif ($_SESSION['user_role'] === 'Manager') {
                header('Location:./manager/mheader.php');
            }elseif($_SESSION['user_role'] === 'Frontdeskofficer'){
                header('Location:./Front_desk_Officer/fheader.php');
            }
            exit;
        } else {
            $error = "Invalid Employee ID or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by foolishdeveloper.com -->
    <title>Staff Login </title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="log.css">
    <!--Stylesheet-->
 
   
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="POST" action="login.php">
        <h3>Staff login</h3>

        <label for="emp_id">Employee ID</label>
        <input type="text" id="emp_id" name="emp_id" required  placeholder="id">

        <label for="password">Password</label>
        <input type="password"  id="password"  name="password"  placeholder="password" required  minlength="8"  pattern="(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Password must be at least 8 characters long, and include at least one uppercase and one lowercase letter."
    > 
    
        <button type="submit" value="Login">Log In</button>
        <?php if (!empty($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        
    </form>
</body>
</html>
