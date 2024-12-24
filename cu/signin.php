<?php
session_start();
require_once 'Auth.php';

$action = $_GET['action'] ?? 'Login';  // Default to login
$error = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new Auth();

    if (isset($_POST['signIn'])) { // Login logic
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($auth->login($email, $password)) {
            header("Location: index.php"); // Redirect to homepage after login
            exit();
        } else {
            $error = "No account found with that email or password. Please sign up.";
            header("Location: signin.php?action=Sign%20Up"); // Redirect to signup page
            exit();
        }
    } elseif (isset($_POST['signUp'])) { // Signup logic
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!empty($name) && !empty($email) && !empty($password)) {
            if ($auth->signup($name, $email, $password)) {
                header("Location: index.php"); // Redirect to homepage after signup
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
    <title>Login</title>
    <link rel="stylesheet" href="./styles/log.css"> <!-- Your CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"> <!-- Swiper CSS -->
</head>
<body>
<div class="container <?php echo ($action === 'Sign Up') ? 'right-panel-active' : ''; ?>" id="container">
    <div class="form-container sign-up-container">
        <!-- Form for Signup -->
        <form method="POST" action="signin.php"> <!-- action is the same file -->
            <h1>Create Account</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your email for registration</span>
            <input type="text" name="name" placeholder="Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" name="signUp">Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <!-- Form for Login -->
        <form method="POST" action="signin.php"> <!-- action is the same file -->
            <h1>Sign in</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your account</span>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <a href="#">Forgot your password?</a>
            <button type="submit" name="signIn">Sign In</button>
        </form>
        <?php if ($error): ?>
            <div style="color: red; padding-left: 60px;">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start your journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>
        Created with <i class="fa fa-heart"></i> by
        <a target="_blank" href="https://florin-pop.com">Florin Pop</a>
        - Read how I created this and how you can join the challenge
        <a target="_blank" href="https://www.florin-pop.com/blog/2019/03/double-slider-sign-in-up-form/">here</a>.
    </p>
</footer>

<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>
</body>
</html>
