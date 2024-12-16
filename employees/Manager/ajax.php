<?php
include '../includes/Database.php';
include 'inserts.php';

 
$db = (new Database())->getConnection();
$movieManager = new inserts($db);

$showManager = new inserts($db);


$action = $_GET['action'] ?? '';

if ($action === 'save_movie') {
    echo $movieManager->saveMovie($_POST, $_FILES);
} elseif ($action === 'delete_movie') {
    echo $movieManager->deleteMovie($_POST['id']);
} else {
    echo 'Invalid action.';
}

if ($action === 'save_showtime') {
    $result = $showManager->saveShowtime($_POST);
    echo $result;  // Return the result (1 for success or error message)
} elseif ($action === 'delete_showtime') {
    $result = $showManager->deleteShowtime($_POST['id']);
    echo $result;  // Return the result (1 for success or error message)
} else {
    echo 'Invalid action.';  // Handle invalid action
}
?>
