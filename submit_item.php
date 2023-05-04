<?php
session_start();

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phase1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Create items table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        user_id INT NOT NULL,
        created_at DATETIME NOT NULL
    )";
if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Check if the user has posted 3 items today
$user_id = $_SESSION['user_id'];
$today = date("Y-m-d H:i:s");
$today_start = date("Y-m-d 00:00:00");
$today_end = date("Y-m-d 23:59:59");
$sql = "SELECT COUNT(*) AS num_posts FROM items WHERE created_at BETWEEN ? AND ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $today_start, $today_end, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['num_posts'] >= 3) {
        die("You have reached the maximum number of posts for today.");
    }
}

// Get form data
$title = $_POST['title'];
$description = $_POST['description'];
$category = $_POST['category'];
$price = $_POST['price'];

if ($row['num_posts'] < 3) {
    // Insert data into database
    $sql = "INSERT INTO items (title, description, category, price, user_id, created_at)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $title, $description, $category, $price, $user_id, $today);
    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "You have reached the maximum number of posts for today.";
}

$conn->close();
?>
