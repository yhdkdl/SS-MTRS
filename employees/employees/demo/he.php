// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header('Location: login.php');
    exit;
}

// Get the user's role
$userRole = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link rel="stylesheet" href="../includes/head.css">

  <!-- Boxicons CSS -->
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

  <!-- Google Font -->
  <style>
    /* Add styles here, or reference an external CSS */
    <?php include "styles.css"; ?>
  </style>
</head>

<body>
  <!-- Sidebar -->
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="../includes/img/logo.png" alt="Logo">
        </span>
        <div class="text logo-text">
          <span class="name">TH Movie System</span>
          <span class="profession"><?php echo htmlspecialchars($userRole); ?></span>
        </div>
      </div>
      <i class='bx bx-chevron-right toggle'></i>
    </header>

    <div class="menu-bar">
      <div class="menu">
        <!-- Search Box -->
        <!-- <li class="search-box">
          <i class='bx bx-search icon'></i>
          <input type="text" placeholder="Search...">
        </li> -->

        <!-- Role-Based Links -->
        <ul class="menu-links">
          <?php if ($userRole === 'Admin'): ?>
            <li class="nav-link">
              <a href="?page=Manage-employe">
                <i class='bx bx-user-plus icon'></i>
                <span class="text nav-text">Manage Employees</span>
              </a>
            </li>
            <li class="nav-link">
              <a href="?page=add-theaters">
              <i class='bx bx-bar-chart-alt-2 icon'></i>
                <span class="text nav-text">Add Theaters</span>
              </a>
            </li>
            <li class="nav-link">
              <a href="?page=feedback">
                <i class='bx bx-comment icon'></i>
                <span class="text nav-text">Feedback</span>
              </a>
            </li>
          <?php elseif ($userRole === 'Manager'): ?>
            <li class="nav-link">
              <a href="?page=manage-schedule">
                <i class='bx bx-calendar icon'></i>
                <span class="text nav-text">Manage Movies</span>
              </a>
            </li>
          <?php elseif ($userRole === 'front_desk officer'): ?>
            <li class="nav-link">
              <a href="?page=view-schedule">
                <i class='bx bx-calendar icon'></i>
                <span class="text nav-text">View Schedule</span>
              </a>
            </li>
            <li class="nav-link">
              <a href="?page=manage-tickets">
                <i class='bx bx-ticket icon'></i>
                <span class="text nav-text">Manage Tickets</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="bottom-content">
        <!-- Logout -->
        <li>
          <a href="../login.php">
            <i class='bx bx-log-out icon'></i>
            <span class="text nav-text">Logout</span>
          </a>
        </li>

        <!-- Dark Mode -->
        <li class="mode">
          <div class="sun-moon">
            <i class='bx bx-moon icon moon'></i>
            <i class='bx bx-sun icon sun'></i>
          </div>
          <span class="mode-text text">Dark mode</span>
          <div class="toggle-switch">
            <span class="switch"></span>
          </div>
        </li>
      </div>
    </div>
  </nav>

  <!-- Content Section -->
  <section class="home">
    <div class="text">Welcome to the Dashboard</div>
  </section>

  <!-- JavaScript -->
  <script>
    const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      // searchBtn = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");

    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });

    // searchBtn.addEventListener("click", () => {
    //   sidebar.classList.remove("close");
    // });

    modeSwitch.addEventListener("click", () => {
      body.classList.toggle("dark");

      if (body.classList.contains("dark")) {
        modeText.innerText = "Light mode";
      } else {
        modeText.innerText = "Dark mode";
      }
    });
  </script>
</body>

</html>