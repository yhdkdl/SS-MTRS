<?php
include 'connect.php';
include 'aut.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the showtime ID from the URL
$showtime_id = $_GET['showtime_id'] ?? null;
if (!$showtime_id) {
    die("Invalid showtime. Please try again.");
}

// Fetch room and showtime details
$query = "SELECT cinemarooms.id AS room_id, cinemarooms.name AS room_name, cinemarooms.base_price,
                 showtime.start_time, showtime.price AS showtime_price
          FROM showtime
          JOIN cinemarooms ON showtime.room_id = cinemarooms.id
          WHERE showtime.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $showtime_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    die("Room or Showtime not found. Please check the database.");
}

// Fetch seats for the room
$seat_query = "SELECT * FROM seats WHERE room_id = ?";
$seat_stmt = $conn->prepare($seat_query);
$seat_stmt->bind_param("i", $room['room_id']);
$seat_stmt->execute();
$seats = $seat_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-image: url(../employees/Manager/assets/img/theater-bg1.jpg);
            background-size: cover;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1, h2 {
            color: #f1f1f1;
        }

        .cinema-room {
            display: grid;
            grid-template-columns: repeat(10, 1fr); /* Adjust based on room layout */
            gap: 10px;
            max-width: 600px;
            margin: auto;
        }

        .seat {
            width: 50px;
            height: 50px;
            background-color: #4CAF50; /* Dark Green for available seats */
            border: 1px solid #333;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .seat.booked {
            background-color: #d32f2f; /* Red for booked seats */
            cursor: not-allowed;
        }

        .seat.selected {
            background-color: #FFD700; /* Yellow for selected seats */
        }

        .seat.temporarily-booked {
            background-color: #9E9E9E; /* Gray for temporarily booked seats */
            cursor: not-allowed;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.6);
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .info-section h3 {
            margin: 0;
            font-size: 18px;
            color: #FFD700; /* Gold for info section titles */
        }

        .info-section ul {
            padding-left: 20px;
        }

        .info-section ul li {
            font-size: 16px;
            margin-bottom: 5px;
            color: #f1f1f1;
        }

        p, button {
            color: #f1f1f1;
        }

        p {
            font-size: 18px;
        }

        .btn {
            background-color: #FFD700; /* Gold for buttons */
            color: #333;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #FFB300; /* Slightly darker yellow on hover */
        }
    </style>
</head>
<body>
    <h1>Room: <?= htmlspecialchars($room['room_name']) ?></h1>
    <h2>Select Your Seats</h2>

    <!-- Information Section for Seat Colors -->
    <div class="info-section">
        <h3>Seat Color Definitions:</h3>
        <ul>
            <li><span style="background-color: #4CAF50; color: white; padding: 5px;">Green:</span> Available for selection</li>
            <li><span style="background-color: #9E9E9E; color: white; padding: 5px;">Gray:</span> Temporarily booked (awaiting confirmation)</li>
            <li><span style="background-color: #d32f2f; color: white; padding: 5px;">Red:</span> Fully booked (cannot be selected)</li>
            <li><span style="background-color: #FFD700; color: black; padding: 5px;">Yellow:</span> Selected by you (not confirmed yet)</li>
        </ul>
    </div>

    <p>Base Price per Seat: $<?= htmlspecialchars($room['base_price']) ?></p>
    <p>Showtime Additional Price: $<?= htmlspecialchars($room['showtime_price']) ?></p>
    <p>Showtime Start: <?= htmlspecialchars($room['start_time']) ?></p>

    <form method="POST" action="process_booking.php">
        <div class="cinema-room">
            <?php while ($seat = $seats->fetch_assoc()): ?>
                <div class="seat 
                    <?= $seat['status'] == 1 ? 'temporarily-booked' : '' ?> 
                    <?= $seat['is_booked'] == 1 ? 'booked' : '' ?>" 
                     data-seat-id="<?= $seat['id'] ?>" 
                     data-base-price="<?= $room['base_price'] ?>"
                     data-showtime-price="<?= $room['showtime_price'] ?>"
                     onclick="selectSeat(this)">
                    <?= htmlspecialchars($seat['seat_number']) ?>
                </div>
            <?php endwhile; ?>
        </div>
        <input type="hidden" name="showtime_id" value="<?= htmlspecialchars($showtime_id) ?>">
        <input type="hidden" name="selected_seats" id="selected_seats">
        <input type="hidden" name="total_price" id="total_price">
        <p>Total Price: $<span id="total_price_display">0.00</span></p>
        <button type="submit" class="btn">Book Selected Seats</button>
    </form>

    <script>
        const selectedSeats = [];
        let totalPrice = 0;

        function selectSeat(seatElement) {
            if (seatElement.classList.contains('booked') || seatElement.classList.contains('temporarily-booked')) return;

            const seatId = seatElement.getAttribute('data-seat-id');
            const basePrice = parseFloat(seatElement.getAttribute('data-base-price'));
            const showtimePrice = parseFloat(seatElement.getAttribute('data-showtime-price'));
            const seatPrice = basePrice + showtimePrice;

            if (selectedSeats.includes(seatId)) {
                // Deselect seat
                selectedSeats.splice(selectedSeats.indexOf(seatId), 1);
                seatElement.classList.remove('selected');
                totalPrice -= seatPrice;
            } else {
                // Select seat
                selectedSeats.push(seatId);
                seatElement.classList.add('selected');
                totalPrice += seatPrice;
            }

            // Update total price and hidden inputs
            document.getElementById('total_price').value = totalPrice.toFixed(2);
            document.getElementById('selected_seats').value = selectedSeats.join(',');
            document.getElementById('total_price_display').textContent = totalPrice.toFixed(2);
        }
    </script>
</body>
</html>


