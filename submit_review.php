<?php
// Start session
session_start();

// Connect to Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phase1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get item id from POST data
$item_id = isset($_POST['id']) ? $_POST['id'] : null;

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id || !ctype_digit(strval($user_id))) {
    die("Error: Invalid user ID");
}

if (!$item_id || !ctype_digit(strval($item_id))) {
    die("Error: Invalid item ID");
}


// Get review data from POST data
$rating = isset($_POST['rating']) ? $_POST['rating'] : null;
$description = isset($_POST['description']) ? $_POST['description'] : null;

// Check if user is the owner of the item
$sql = "SELECT user_id FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$item_owner_id = $row['user_id'];

if ($item_owner_id == $user_id) {
    die("Sorry, you cannot review your own item.");
}

// Check if user has already given 3 reviews today
$today = date('Y-m-d');
$sql = "SELECT COUNT(*) AS review_count FROM reviews WHERE user_id = ? AND created_at >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$review_count = $row['review_count'];

if ($review_count >= 3) {
    die("Sorry, you have already given 3 reviews today.");
}

// Check if user has already reviewed the item
$sql = "SELECT COUNT(*) AS review_count FROM reviews WHERE user_id = ? AND item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$review_count = $row['review_count'];

if ($review_count > 0) {
    die("Sorry, you have already reviewed this item.");
}

// Retrieve user_id from users table
$sql = "SELECT user_id FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Error: User not found.");
}

// Insert review data into database
$created_at = date('Y-m-d H:i:s');
$sql = "INSERT INTO reviews (item_id, user_id, rating, description, created_at) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiss", $item_id, $user_id, $rating, $description, $created_at);
if ($stmt->execute() === FALSE) {
    die("Error inserting review: " . $conn->error);
}

echo "Review added successfully.";
?>

