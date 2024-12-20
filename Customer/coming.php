<section class="coming" id="coming">
    <h2>Coming Soon</h2>
    <div class="movie-slider-container">
        <!-- Slider Wrapper -->
        <div class="movie-slider">
            <?php
            $query = "SELECT * FROM movies WHERE status = 'inactive'";
            $result = mysqli_query($conn, $query);
            while ($movie = mysqli_fetch_assoc($result)) {
                echo "<div class='movie-item'>
                    <div class='box-img'>
                        <img src='../employees/Manager/assets/img/{$movie['cover_img']}' alt='{$movie['title']}'>
                       
                    </div>
                    <h2>{$movie['title']}</h2>
                    <p>{$movie['duration']} min | {$movie['description']}</p>
                    <p>Release Date: {$movie['release_date']}</p>
                    <a href='{$movie['youtube_link']}' class='trailer-btn'>Watch Trailer</a>
                </div>";
            }
            ?>
        </div>
        <!-- Navigation Arrows -->
        <button class="slider-btn prev-btn">&lt;</button>
        <button class="slider-btn next-btn">&gt;</button>
    </div>
</section>

<style>
    /* Movies Section Styles */
    .movie-slider-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        padding: 2rem 0;
    }

    .movie-slider {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .movie-item {
        flex: 0 0 auto; /* Prevents wrapping */
        width: 300px;
        margin: 0 10px;
        text-align: center;
        color: #fff;
    }

    .box-img {
        position: relative;
    }

    .box-img img {
        width: 100%;
        border-radius: 8px;
    }


    h2 {
        margin: 1rem 0;
        font-size: 1.5rem;
        color: #ffcc00;
    }

    p {
        margin: 0.5rem 0;
    }

    .trailer-btn {
        margin-top: 0.5rem;
        padding: 0.8rem 1.2rem;
        display: inline-block;
        background: linear-gradient(45deg, #ff6f61, #d6336c);
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .trailer-btn:hover {
        background: linear-gradient(45deg, #d6336c, #ff6f61);
        transform: scale(1.05);
    }

    /* Navigation Buttons */
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        border: none;
        padding: 1rem;
        cursor: pointer;
        font-size: 1.5rem;
        z-index: 10;
    }

    .prev-btn {
        left: 0;
    }

    .next-btn {
        right: 0;
    }

    .slider-btn:hover {
        background: rgba(255, 255, 255, 0.8);
        color: #000;
    }
</style>

<script>
    // JavaScript for Slider Functionality
    const slider = document.querySelector('.movie-slider');
    const nextBtn = document.querySelector('.next-btn');
    const prevBtn = document.querySelector('.prev-btn');
    const totalItems = document.querySelectorAll('.movie-item').length;
    const itemWidth = 320; // 300px + 10px margin on both sides
    const sliderWidth = totalItems * itemWidth; // Total width of the items
    let currentIndex = 0;

    // Set slider width dynamically to accommodate all items
    slider.style.width = `${sliderWidth}px`;

    // Next button functionality
    nextBtn.addEventListener('click', () => {
        if (currentIndex < totalItems - 1) {
            currentIndex += 1;
        } else {
            currentIndex = 0; // Loop back to the beginning
        }
        slider.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
    });

    // Prev button functionality
    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex -= 1;
        } else {
            currentIndex = totalItems - 1; // Loop back to the last item
        }
        slider.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
    });
</script>
