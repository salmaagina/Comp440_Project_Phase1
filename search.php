<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    </head>
<body>
    <div class="container">
    
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

<a href="index.php" class="btn btn-warning">return</a>
    </div>
</body>
</html>