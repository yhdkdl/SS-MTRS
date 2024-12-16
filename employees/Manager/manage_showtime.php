<?php 
include '../includes/Database.php';
include 'inserts.php';
 
$db = (new Database())->getConnection();

$showManager = new inserts($db);

$movies = $showManager->getAllMovies();
$rooms = $showManager->getAllRooms();
$id = $_GET['id'] ?? null;

if ($id) {
    $showtime = $showManager->getShowById($id);
}
?>

<form id="manage-showtime">
    <input type="hidden" name="id" value="<?= isset($showtime['id']) ? $showtime['id'] : ''; ?>">

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

    <div class="form-group">
        <label>Start Time</label>
        <input type="datetime-local" name="start_time" class="form-control" value="<?= isset($showtime['start_time']) ? date('Y-m-d\TH:i', strtotime($showtime['start_time'])) : ''; ?>" required>
    </div>
</form>


<script>
$('#save_showtime').click(function () {
    const form = $('#manage-showtime');

    // Check if all fields are filled before sending the request
    if (!$("select[name='movie_id']").val() || !$("select[name='room_id']").val() || !$("input[name='start_time']").val()) {
        alert('Please fill in all the fields.');
        return;  // Prevent submission if any required field is empty
    }

    $.ajax({
        url: 'ajax.php?action=save_showtime',  // Ensure the action matches the server-side check
        method: 'POST',
        data: form.serialize(),  // Serialize the form data
        success: function (response) {
            if (response.trim() == '1') {
                alert('Showtime successfully saved.');
                location.reload();
            } else {
                alert('Showtime successfully  saved');
                    location.reload(); // Display the response if something goes wrong
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + error);  // Log error details for debugging
            alert('Error saving showtime.');
        }
    });
});




</script>
