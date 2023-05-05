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
if($result = $conn->query("SELECT * FROM users")) {
                //Increment the button click count
                $_SESSION['button_click_count']++;
            
    if($count = $result->num_rows) {
        echo "<table>";
        echo "<tr><th>UserId</th><th>Username</th>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        }
    }
}



        ?>
        <a href="index.php" class="btn btn-warning">return</a>
    </div>
</body>
</html>