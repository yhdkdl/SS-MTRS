<?php

include('connect.php'); // Ensure this file path is correct
session_start();
$action = $_GET['action'] ?? 'Login';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $email = mysqli_real_escape_string($conn, $_POST['email']);
//     $password = mysqli_real_escape_string($conn, $_POST['password']);
//     $action = $_POST['action'];

//     if ($action === 'Login') {
//         $query = "SELECT * FROM customers WHERE email = '$email'";
//         $result = mysqli_query($conn, $query);

//         if ($result && mysqli_num_rows($result) > 0) {
//             $customer = mysqli_fetch_assoc($result);
//             if (password_verify($password, $customer['password'])) {
//                 $_SESSION['customer_name'] = $customer['name'];
//                 $_SESSION['customer_id'] = $customer['id'];
//                 header("Location: index.php");
//                 exit();
//             } else {
//                 $error = "Invalid password.";
//             }
//         } else {
//             $error = "No user found with this email.";
//         }
//     } elseif ($action === 'Sign Up') {
//         // Sign-up logic here
//         $name = $_POST['name'] ?? '';
//         if (!empty($email) && !empty($password) && !empty($name)) {
//             $query = "INSERT INTO customers (name, email, password) VALUES ('$name', '$email', '$password')";
//             if (mysqli_query($conn, $query)) {
//                 $_SESSION['user'] = ['id' => mysqli_insert_id($conn), 'name' => $name, 'email' => $email];
//                 header("Location: index.php");
//                 exit();
//             } else {
//                 $error = "Failed to sign up. Try again later.";
//             }
//         } else {
//             $error = "All fields are required.";
//         }
//     }
// }



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $action = $_POST['action'] ?? 'Login';

    if ($action === 'Login') {
        $query = "SELECT * FROM customers WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } elseif ($action === 'Sign Up') {
        // Validate fields and insert new customer
        $name = $_POST['name'] ?? '';
        if (!empty($email) && !empty($password) && !empty($name)) {
            $query = "INSERT INTO customers (name, email, password) VALUES ('$name', '$email', '$password')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['user'] = ['id' => mysqli_insert_id($conn), 'name' => $name, 'email' => $email];
                header("Location: index.php");
                exit();
            } else {
                $error = "Failed to sign up. Try again later.";
            }
        } else {
            $error = "All fields are required.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $action; ?></title>
    <link rel="stylesheet" href="../Customer/styles/LoginSignup.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="text"><?php echo $action; ?></div>
            <div class="underline"></div>
        </div>
        <form method="POST" action="signin.php" class="inputs">
            <?php if ($action === "Sign Up"): ?>
                <div class="input">
                    <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name ?? ""); ?>">
                </div>
            <?php endif; ?>

            <div class="input">
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ""); ?>">
            </div>

            <div class="input">
                <input type="password" name="password" placeholder="Password">
            </div>

            <?php if ($action === "Login"): ?>
                <div class="forget"><a href="forgot_password.php">Forgot Password?</a></div>
            <?php endif; ?>

            <!-- Display errors -->
            <?php if (!empty($errors)): ?>
                <div style="color: red; padding-left: 60px;">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
                
            <?php endif;  //include "../employees/Manager/assets/img/"?>

            <div class="submit-container">
                <button type="submit" name="action" value="Sign Up" class="submit <?php echo $action === 'Login' ? 'gray' : ''; ?>">Sign Up</button>
                <button type="submit" name="action" value="Login" class="submit <?php echo $action === 'Sign Up' ? 'gray' : ''; ?>">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
