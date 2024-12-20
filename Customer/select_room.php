<?php
include 'connect.php';
include 'aut.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the movie ID from the URL
$movie_id = $_GET['id'] ?? null;
if (!$movie_id) {
    die("Invalid movie selection. Please try again.");
}

// Fetch the selected movie details to get the background image
$query = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$movie_result = $stmt->get_result();
$movie = $movie_result->fetch_assoc();

if (!$movie) {
    die("Movie not found.");
}

// Fetch available rooms and showtimes for the selected movie
$query = "SELECT showtime.id AS showtime_id, cinemarooms.name AS room_name, 
                 showtime.start_time, cinemarooms.base_price, showtime.price AS showtime_price
          FROM showtime
          JOIN cinemarooms ON showtime.room_id = cinemarooms.id
          WHERE showtime.movie_id = ?
          ORDER BY showtime.start_time ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$showtimes = $stmt->get_result();

$showtime_message = null;
if ($showtimes->num_rows === 0) {
    $showtime_message = "No showtimes available for this movie yet. Please check back later.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Room - Triangle Cinema</title>
    <link rel="stylesheet" href="../Customer/styles/style.css"> <!-- Your CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"> <!-- Swiper CSS -->
    <style>
        /* Dynamically set the background image to the selected movie's cover image */
        

        .container {
            max-width: 968px;
            margin: 3rem auto;
            padding: 1rem;
            background: url('../employees/Manager/assets/img/<?= htmlspecialchars($movie['cover_img']); ?>') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .no-showtime-message {
    background-color: #ffdddd;
    color: #a6041a;
    padding: 1rem;
    margin: 2rem 0;
    text-align: center;
    border: 1px solid #a6041a;
    border-radius: 5px;
    font-weight: bold;
    font-size: 1.2rem;
}


        h1 {
            text-align: center;
            color: var(--bg-color);
            margin-bottom: 2rem;
            font-size: 2.5rem;
            text-shadow: 2px 2px var(--main-color);
        }

        .showtime-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .showtime-item {
            position: relative;
            background: rgba(0, 0, 0, 0.7); /* Darken the background */
            padding: 1.5rem;
            border-radius: 0.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            color: var(--bg-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .showtime-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .showtime-details h3 {
            font-size: 1.5rem;
            color: var(--main-color);
            margin-bottom: 0.5rem;
        }

        .showtime-details p {
            margin: 0.4rem 0;
            font-size: 1rem;
        }

        .btn {
            padding: 0.8rem;
            text-align: center;
            background: var(--main-color);
            color: var(--bg-color);
            font-weight: bold;
            text-transform: uppercase;
            border: none;
            border-radius: 0.3rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #a6041a;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Room Selection Section -->
    <div class="container">
    <h1>Rooms Showing  Movie: <?= htmlspecialchars($movie['title']); ?> please select the room you want to proceed</h1>

    <?php if ($showtime_message): ?>
        <div class="no-showtime-message">
            <?= htmlspecialchars($showtime_message); ?>
        </div>
    <?php else: ?>
        <div class="showtime-list">
            <?php while ($row = $showtimes->fetch_assoc()): ?>
                <div class="showtime-item">
                    <div class="showtime-details">
                        <h3><?= htmlspecialchars($row['room_name']) ?></h3>
                        <p>Showtime: <?= htmlspecialchars($row['start_time']) ?></p>
                        <p>Base Price per Seat: $<?= htmlspecialchars($row['base_price']) ?></p>
                        <p>Additional ShowtimePrice: $<?= htmlspecialchars($row['showtime_price']) ?></p>
                    </div>
                    <a href="select_seats.php?showtime_id=<?= htmlspecialchars($row['showtime_id']) ?>" class="btn">Select</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>
        <!-- Include Movies Section -->
    <?php include 'movies.php'; ?>

    <!-- Include Coming Soon Section -->
    <?php include 'coming.php'; ?>

    <!-- Include Newsletter Section -->
    <?php include 'newsletter.php'; ?>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Your custom JS -->
    <script src="../Customer/scripts/script.js"></script>
</body>
</html>
