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
        <h1>GUI</h1>
        <br>

        <form action="" method="get">
            <button type="submit" name="action" value="most_expensive_items" class="btn btn-primary">Most Expensive Items in Each Category</button>
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
            <br><br>
            <input type="text" class="form-control" placeholder="Enter username of user X" name="user_x">
            <br>
            <button type="submit" name="action" value="user_x_items" class="btn btn-primary">Items posted by user X with excellent/good comments</button>
            <br><br>
            <button type="submit" name="action" value="user_who_posted_most" class="btn btn-primary">Users Who Posted the Most Number of Items Since 5/1/2020</button>
            <form action="" method="get">
    <div class="row">
        <div class="col">
            <label for="fav_userX">Select User X:</label>
            <select class="form-control" id="fav_userX" name="fav_userX">
                <?php
                    // Get all the distinct usernames in the database
                    $sql = "SELECT DISTINCT username FROM users";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["username"] . "'>" . $row["username"] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="col">
            <label for="fav_userY">Select User Y:</label>
            <select class="form-control" id="fav_userY" name="fav_userY">
                <?php
                    // Get all the distinct usernames in the database
                    $sql = "SELECT DISTINCT username FROM users";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["username"] . "'>" . $row["username"] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
    </div>
    <br>
    <button type="submit" name="action" value="favorited_by_both" class="btn btn-primary">Find Users Who are Favorited by Both X and Y</button>
    <br><br>
            <button type="submit" name="action" value="user_who_posted_excellent" class="btn btn-primary">Users who never posted any "excellent" items</button>
            <br><br>
            <button type="submit" name="action" value="user_who_posted_poor" class="btn btn-primary">Users who never posted a "poor" review.</button>
            <br><br>
            <button type="submit" name="action" value="users_only_poor_reviews" class="btn btn-primary">Users who posted some reviews, but each of them is "poor"</button>
            <br><br>
            <button type="submit" name="action" value="users_items_no_poor_reviews" class="btn btn-primary">Users whose items never received any poor reviews</button>
            <br><br>
            <button type="submit" name="action" value="user_pairs_always_excellent" class="btn btn-primary">User pairs who always gave each other excellent reviews</button>
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
        // Check if the "Items posted by user X with excellent/good comments" button is pressed
        if (isset($_GET['action']) && $_GET['action'] == 'user_x_items') {
        // Get the input username
        $user_x = $_GET['user_x'];

        // Get the items posted by user X with only "Excellent" or "Good" comments
        $sql = "SELECT items.*
            FROM items
            JOIN reviews ON items.id = reviews.item_id
            WHERE items.user_id = (SELECT user_id FROM users WHERE username = '$user_x')
            AND reviews.rating IN ('Excellent', 'Good')
            AND NOT EXISTS (
                SELECT 1 FROM reviews r2
                WHERE r2.item_id = items.id
                AND r2.rating NOT IN ('Excellent', 'Good')
            )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
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

// Check if the "Users who posted the most number of items" button is pressed
if (isset($_GET['action']) && $_GET['action'] == 'user_who_posted_most') {
    // Increment the button click count
    $_SESSION['button_click_count']++;

    // Get the users who have posted the most number of items since 5/1/2020 (inclusive)
    $sql = "SELECT users.user_id, users.username, COUNT(items.id) as num_items
            FROM users 
            JOIN items ON users.user_id = items.user_id 
            WHERE items.created_at >= '2020-05-01'
            GROUP BY users.user_id, users.username
            HAVING COUNT(items.id) = (
                SELECT COUNT(items.id)
                FROM items
                WHERE items.created_at >= '2020-05-01'
                GROUP BY items.user_id
                ORDER BY COUNT(items.id) DESC
                LIMIT 1
            )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0 && $_SESSION['button_click_count'] % 2 == 1) {
        // Output the results
        echo "<table>";
        echo "<tr><th>User ID</th><th>Username</th><th>Number of Items</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["num_items"] . "</td>";
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

// Check if the "Find Users Who are Favorited by Both X and Y" button is pressed
if (isset($_GET['action']) && $_GET['action'] == 'favorited_by_both') {
    // Get the input usernames
    $user_x = $_GET['fav_userX'];
    $user_y = $_GET['fav_userY'];

    // Get the users who are favorited by both X and Y
    $sql = "SELECT t1.Users
    FROM favorites t1
    JOIN favorites t2
    ON t1.Users = t2.Users AND t1.Favorited_by = '$user_x' AND t2.Favorited_by = '$user_y'
    AND t1.Users = t2.Users";
    
    $result = $conn->query($sql);

    // Output the results
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>User</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Users"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}



// Requirement 8: Users who posted some reviews, but each of them is "poor"
if (isset($_GET['action']) && $_GET['action'] == 'users_only_poor_reviews') {
    $sql = "SELECT u.user_id, u.username
            FROM users u
            WHERE NOT EXISTS (
                SELECT 1
                FROM reviews r
                WHERE r.user_id = u.user_id AND r.rating <> 'poor'
            )
            AND EXISTS (
                SELECT 1
                FROM reviews r
                WHERE r.user_id = u.user_id AND r.rating = 'poor'
            )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>User ID</th><th>Username</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// Requirement 9: Users whose items never received any poor reviews
if (isset($_GET['action']) && $_GET['action'] == 'users_items_no_poor_reviews') {
    $sql = "SELECT DISTINCT u.user_id, u.username
            FROM users u
            JOIN items i ON u.user_id = i.user_id
            WHERE NOT EXISTS (
                SELECT 1
                FROM reviews r
                WHERE r.item_id = i.id AND r.rating = 'poor'
            )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>User ID</th><th>Username</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// Requirement 10: User pairs who always gave each other excellent reviews
if (isset($_GET['action']) && $_GET['action'] == 'user_pairs_always_excellent') {
    $sql = "SELECT u1.username as user1, u2.username as user2
            FROM users u1
            JOIN users u2 ON u1.user_id <> u2.user_id
            WHERE NOT EXISTS (
                SELECT 1
                FROM reviews r1
                JOIN items i1 ON r1.item_id = i1.id
                WHERE r1.user_id = u2.user_id
                AND i1.user_id = u1.user_id
                AND r1.rating <> 'excellent'
            )
            AND NOT EXISTS (
                SELECT 1
                FROM reviews r2
                JOIN items i2 ON r2.item_id = i2.id
                WHERE r2.user_id = u1.user_id
                AND i2.user_id = u2.user_id
                AND r2.rating <> 'excellent'
            )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>User 1</th><th>User 2</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user1"] . "</td>";
            echo "<td>" . $row["user2"] . "</td>";
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
