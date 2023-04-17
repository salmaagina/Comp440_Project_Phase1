<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phase1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully<br>";
} else {
  echo "Error creating database: " . $conn->error;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE reviews(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        item_id INT(6) UNSIGNED NOT NULL,
        user_id INT(6) UNSIGNED NOT NULL,
        rating VARCHAR(10) NOT NULL,
        description TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Get item id and user id from POST data
$item_id = $_POST['item_id'];
$user_id = $_POST['user_id'];

// Check if user is the owner of the item
$sql = "SELECT * FROM items WHERE id = $item_id AND user_id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "Sorry, you cannot review your own item.";
    $conn->close();
    exit();
}

// Check if user has already given 3 reviews today
$today = date('Y-m-d');
$sql = "SELECT * FROM reviews WHERE user_id = $user_id AND created_at >= '$today'";
$result = $conn->query($sql);
if ($result->num_rows >= 3) {
    echo "Sorry, you have already given 3 reviews today.";
    $conn->close();
    exit();
}

// Get review data from POST data
$rating = $_POST['rating'];
$description = $_POST['description'];

// Insert review into database
$sql = "INSERT INTO reviews (item_id, user_id, rating, description) VALUES ($item_id, $user_id, '$rating', '$description')";
if ($conn->query($sql) === TRUE) {
    echo "Review submitted successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
