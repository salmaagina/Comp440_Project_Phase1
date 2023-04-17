<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phase1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query
$category = $_GET['category'];

// Search items by category
$sql = "SELECT * FROM items WHERE category LIKE '%" . $category . "%'";
$result = $conn->query($sql);

// Display search results in a table
if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Title</th><th>Description</th><th>Category</th><th>Price</th><th>Created At</th><th>Review?</th></tr>";
    while ($row = $result->fetch_assoc()) {
        // Create a button for each item and link it to a reviews page
        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['description'] . "</td><td>" . $row['category'] . "</td><td>" . $row['price'] . "</td><td>" . $row['created_at'] . "</td><td><a href='reviews.php?id=" . $row['id'] . "'>Review</a></td></tr>";
    }
    echo "</table>";
} else {
    echo "No items found.";
}

$conn->close();
?>
