<?php 
include '../includes/Database.php';
include 'inserts.php';
 
$db = (new Database())->getConnection();

$showManager = new inserts($db);

$movies = $showManager->getAllactiveMovies();
$rooms = $showManager->getAllRooms();
$id = $_GET['id'] ?? null;

if ($id) {
    $showtime = $showManager->getShowById($id);
}
?>

<form id="manage-showtime">
    <input type="hidden" name="id" value="<?= isset($showtime['id']) ? $showtime['id'] : ''; ?>">

    <!-- Movie Dropdown -->
    <div class="form-group">
        <label>Movie</label>
        <select name="movie_id" class="form-control" required>
            <option value="" disabled selected>Select Movie</option>
            <?php foreach ($movies as $movie): ?>
                <option value="<?= $movie['id']; ?>" <?= isset($showtime['movie_id']) && $showtime['movie_id'] == $movie['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($movie['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Room Dropdown -->
    <div class="form-group">
        <label>Room</label>
        <select name="room_id" class="form-control" required>
            <option value="" disabled selected>Select Room</option>
            <?php foreach ($rooms as $room): ?>
                <option value="<?= $room['id']; ?>" <?= isset($showtime['room_id']) && $showtime['room_id'] == $room['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($room['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Price Field -->
    <div class="form-group">
        <label>Price</label>
        <input type="number" name="price" class="form-control" value="<?= isset($showtime['price']) ? $showtime['price'] : ''; ?>" step="0.01" min="0" required>
    </div>

    <!-- Start Time -->
    <div class="form-group">
        <label>Start Time</label>
        <input type="datetime-local" name="start_time" class="form-control" value="<?= isset($showtime['start_time']) ? date('Y-m-d\TH:i', strtotime($showtime['start_time'])) : ''; ?>" required>
    </div>
</form>

<!-- Save Showtime Script -->
<script>
$('#save_showtime').click(function () {
    const form = $('#manage-showtime');

    // Validate form inputs
    if (!$("select[name='movie_id']").val() || !$("select[name='room_id']").val() || !$("input[name='start_time']").val()) {
        alert('Please fill in all the fields.');
        return;
    }

    $.ajax({
        url: 'ajax.php?action=save_showtime',
        method: 'POST',
        data: form.serialize(),
        success: function (response) {
            if (response.trim() !== '1') {
                alert('Showtime successfully saved.');
                location.reload();
            } else {
                alert('Error while saving: ' + response);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + error);
            alert('Error saving showtime.');
        }
    });
});
</script>
