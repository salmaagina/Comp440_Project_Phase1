<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phase1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="container">
        <h1>Select a button</h1>
        <br>

        <!-- Make the most expensive items show -->
        <form action="" method="get">
            <button type="submit" name="action" value="most_expensive_items" class="btn btn-primary">Most Expensive Items in Each Category</button>
            <button type="submit" name="action" value="user_who_posted" class="btn btn-primary">Users Who Have Posted</button>
            <button type="submit" name="action" value="user_who_posted_excellent" class="btn btn-primary">Users who never posted any "excellent" items</button>
            <button type="submit" name="action" value="user_who_posted_poor" class="btn btn-primary">Users who never posted a "poor" review.</button>
            <br><br>
            <div class="row">
              <div class="col">
                <input type="text" class="form-control" placeholder="Category X" name="category_x">
              </div>
              <div class="col">
                <input type="text" class="form-control" placeholder="Category Y" name="category_y">
              </div>
            </div>
            <br>
            <button type="submit" name="action" value="user_who_posted_same_day" class="btn btn-primary">Users Who Posted at least two items that are posted on the same day</button>
        </form>
        
        <?php
        // Set the session variable to 0 if it doesn't exist
        if (!isset($_SESSION['button_click_count'])) {
            $_SESSION['button_click_count'] = 0;
        }

        // Check if the "Most Expensive Items in Each Category" button is pressed
        if (isset($_GET['action']) && $_GET['action'] == 'most_expensive_items') {
            // Increment the button click count
            $_SESSION['button_click_count']++;
            // Get the most expensive item in each category
            $sql = "SELECT * FROM items WHERE price IN (SELECT MAX(price) FROM items GROUP BY category)";
            $result = $conn->query($sql);
            if ($result->num_rows > 0 && $_SESSION['button_click_count'] % 2 == 1) {
                // Output the results
                echo "<table>";
                echo "<tr><th>Title</th><th>Description</th><th>Price</th><th>Category</th></tr>";
                while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["title"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>" . $row["price"] . "</td>";
                echo "<td>" . $row["category"] . "</td>";
                echo "</tr>";
                }
                echo "</table>";
                }
                }
                    // Check if the "Users who have posted" button is pressed
    if (isset($_GET['action']) && $_GET['action'] == 'user_who_posted') {
        // Increment the button click count
        $_SESSION['button_click_count']++;
        // Get the users who have posted
        $sql = "SELECT DISTINCT users.user_id, users.username FROM users JOIN items ON users.user_id = items.user_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0 && $_SESSION['button_click_count'] % 2 == 1) {
            // Output the results
            echo "<table>";
            echo "<tr><th>User ID</th><th>Username</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["user_id"] . "</td>";
                echo "<td>" . $row["username"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }

// Check if the "Users who have never posted an excellent item" button is pressed
if (isset($_GET['action']) && $_GET['action'] == 'user_who_posted_excellent') {
    // Increment the button click count
    $_SESSION['button_click_count']++;

    // Get the users who have never posted an "excellent" item
    $sql = "SELECT DISTINCT users.user_id, users.username FROM users 
            WHERE users.user_id NOT IN (
                SELECT items.user_id FROM items 
                JOIN reviews ON items.id = reviews.item_id 
                WHERE reviews.rating = 'excellent' 
                GROUP BY items.user_id 
                HAVING COUNT(DISTINCT items.id) >= 3
            )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0 && $_SESSION['button_click_count'] % 2 == 1) {
        // Output the results
        echo "<table>";
        echo "<tr><th>User ID</th><th>Username</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}


    // Check if the "Users who have never posted a poor review" button is pressed
    if (isset($_GET['action']) && $_GET['action'] == 'user_who_posted_poor') {
        // Increment the button click count
        $_SESSION['button_click_count']++;
        // Get the users who have never posted a "poor" review
        $sql = "SELECT DISTINCT users.user_id, users.username FROM users LEFT JOIN reviews ON users.user_id = reviews.user_id WHERE reviews.rating <> 'poor' OR reviews.rating IS NULL";
        $result = $conn->query($sql);
        if ($result->num_rows > 0 && $_SESSION['button_click_count'] % 2 == 1) {
            // Output the results
            echo "<table>";
            echo "<tr><th>User ID</th><th>Username</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["user_id"] . "</td>";
                echo "<td>" . $row["username"] . "</td>";
                echo "</tr>";
                }
                echo "</table>";
                }
                }
// Check if the "Users who posted at least two items on the same day" button is pressed
if (isset($_GET['action']) && $_GET['action'] == 'user_who_posted_same_day') {
    // Get the input category values
    $category_x = $_GET['category_x'];
    $category_y = $_GET['category_y'];
// Get the users who have posted at least two items on the same day in the given categories
$sql = "SELECT DISTINCT i1.user_id, u.username
FROM items i1
JOIN items i2 ON i1.user_id = i2.user_id AND i1.id <> i2.id AND DATE(i1.created_at) = DATE(i2.created_at)
JOIN users u ON i1.user_id = u.user_id
WHERE i1.category = '$category_x' AND i2.category = '$category_y'";
$result = $conn->query($sql);

// Output the results
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>User ID</th><th>Username</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
}
    ?>
    <a href="index.php" class="btn btn-secondary">Return to Index</a>
</div>
</body>
</html>
