<?php
// Include the database connection file
include 'connect.php';
include 'aut.php';

// Fetch movies with 'inactive' status
$query = "SELECT * FROM movies WHERE status='inactive'";
$result = mysqli_query($conn, $query);

$movies = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $movies[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triangle Cinema</title>
    <link rel="stylesheet" href="../Customer/styles/style.css"> <!-- Your CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"> <!-- Swiper CSS -->
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Home Section -->
    <section class="home swiper" id="home">
        <div class="swiper-wrapper">
            <?php if (!empty($movies)): ?>
                <?php foreach ($movies as $movie): ?>
                    <div class="swiper-slide">
                        <div class="container">
                            <img src="../employees/Manager/assets/img/<?= htmlspecialchars($movie['cover_img']); ?>" 
                                 alt="<?= htmlspecialchars($movie['title']); ?>">
                            <div class="home-text">
                                <span>Coming soon</span>
                                <h1><?= htmlspecialchars($movie['title']); ?> <br> <?= htmlspecialchars($movie['duration']); ?> min</h1>
                                <a href="{$movie['youtube_link']} "class='trailer-btn'>Watch Trailer</a>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No movies currently showing.</p>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </section>

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
<script>
    const swiper = new Swiper('.swiper', {
    loop: true, // Enable looping
    autoplay: {
        delay: 3000, // 3 seconds delay between slides
        disableOnInteraction: false, // Continue autoplay after interaction
    },
    pagination: {
        el: '.swiper-pagination', // Pagination bullets
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next', // Next button
        prevEl: '.swiper-button-prev', // Previous button
    },
});
</script>
    <!-- Swiper Initialization Script -->
    

    <!-- Your custom JS -->
    <script src="../Customer/scripts/script.js"></script>
</body>
</html>
