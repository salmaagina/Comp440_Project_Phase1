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

//Create items table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        user_ip VARCHAR(255) NOT NULL,
        created_at DATETIME NOT NULL
    )";
if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Check if the user has posted 3 items today
$user_ip = $_SERVER['REMOTE_ADDR'];
$today = date("Y-m-d");
$today_start = $today . " 00:00:00";
$today_end = $today . " 23:59:59";
$sql = "SELECT COUNT(*) AS num_posts FROM items WHERE created_at BETWEEN ? AND ? AND user_ip = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $today_start, $today_end, $user_ip);
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

// Insert data into database
$sql = "INSERT INTO items (title, description, category, price, user_ip, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $title, $description, $category, $price, $user_ip);
if ($stmt->execute() === TRUE) {
echo "New record created successfully";
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
