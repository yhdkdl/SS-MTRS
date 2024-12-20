coming
<!-- <section class="coming" id="coming">
    <h2>Coming Soon</h2>
    <div class="coming-container">
        <?php
        $query = "SELECT * FROM movies WHERE status='coming_soon'";
        $result = mysqli_query($conn, $query);
        while ($movie = mysqli_fetch_assoc($result)) {
            echo "<div class='box'>
                <div class='box-img'>
                    <img src='../manager/assets/img/1600222200_avengersendgame-20190417122917-18221.jpg' alt='{$movie['title']}'>
                </div>
                <h3>{$movie['title']}</h3>
                <span>Release Date: {$movie['release_date']}</span>
            </div>";
        }
        ?>
    </div>
</section> -->

<?php
// Include the database connection
include '../Database/connection.php';  // Adjust the path if needed
?>
<section class="movies" id="movies">
    <h2>Movies</h2>
    <div class="movies-container">
        <?php
        // Make sure the connection is available
        $query = "SELECT * FROM movies WHERE release_date <= CURDATE() ORDER BY release_date DESC";

        $result = mysqli_query($conn, $query);
        while ($movie = mysqli_fetch_assoc($result)) {
            echo "<div class='box'>
                <div class='box-img'>
                    <img src='../{$movie['image_url']}' alt='{$movie['title']}'>
                </div>
                <h3>{$movie['title']}</h3>
                <span>{$movie['duration']} min | {$movie['genre']}</span>
            </div>";
        }
        ?>
    </div>
</section>
