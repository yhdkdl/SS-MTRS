<?php

// Database connection
    $servername = "localhost"; // Adjust as needed
    $username = "root"; // Adjust as needed
    $password_db = ""; // Adjust as needed
    $dbname = "th_ctms"; // Adjust as needed

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    ?>