<?php
include 'connect.php';
include 'aut.php';

// Get movie ID from the query parameter
if (!isset($_GET['id'])) {
    echo "Invalid movie ID.";
    exit;
}

$movie_id = intval($_GET['id']);

// Fetch movie details from the database
$query = "SELECT * FROM movies WHERE id = $movie_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Movie not found.";
    exit;
}

$movie = mysqli_fetch_assoc($result);

// Fetch available showtimes
$showtime_query = "SELECT * FROM showtimes WHERE movie_id = $movie_id AND available_seats > 0";
$showtime_result = mysqli_query($conn, $showtime_query);

$showtimes = [];
if ($showtime_result && mysqli_num_rows($showtime_result) > 0) {
    while ($row = mysqli_fetch_assoc($showtime_result)) {
        $showtimes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <h1>Book "<?php echo htmlspecialchars($movie['title']); ?>"</h1>
    <form action="process_booking.php" method="POST">
        <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">

        <label for="showtime">Select Time Range:</label>
        <select name="showtime_id" id="showtime" required>
            <?php foreach ($showtimes as $showtime): ?>
                <option value="<?php echo $showtime['id']; ?>">
                    <?php echo date('h:i A', strtotime($showtime['start_time'])) . " - " . date('h:i A', strtotime($showtime['end_time'])); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="seats">Number of Seats:</label>
        <input type="number" name="seats" id="seats" min="1" max="10" required>

        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="CBE">CBE</option>
            <option value="TELEBIRR">TELEBIRR</option>
            <option value="BOA">BOA</option>
            <option value="EBIRR">EBIRR</option>
        </select>

        <button type="submit" class="btn-confirm-booking">Confirm Booking</button>
    </form>
</body>
</html>
