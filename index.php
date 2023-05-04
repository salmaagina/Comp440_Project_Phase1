<?php
session_start();
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
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// $_SESSION['user_id'] = $user_id;
// if (isset($_SESSION['user_id'])) {
//     echo "User ID: " . $_SESSION['user_id'];
// } else {
//     die("You need to log in to view this page.");
// }
?>
<body>
    <div class="container">
        <h1>Welcome to COMP 440</h1>
        <a href="logout.php" class="btn btn-warning">Logout</a>
        <form method = "post" action = "initialize_database.php">
            <button type = "submit" name = "initialize">Initialize Database</button>
        </form>
        <br>
        <form action="submit_item.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title"><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea><br>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category"><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price"><br>

            <button type="submit" value="Submit">Submit</button>
        </form>
        <form method="GET" action="search.php">
            <label for="category">Search by category:</label>
            <input type="text" id="category" name="category">
            <input type="submit" value="Search">
        </form>
    </div>
</body>
</html>