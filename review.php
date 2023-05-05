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
<body>
    <div class="container">
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        
            // Set the item_id variable from the query string parameter
            $item_id = isset($_GET['id']) ? $_GET['id'] : null;
        ?>
        <form method="POST" action="submit_review.php">
            <input type="hidden" name="id" value="<?php echo $item_id ?>">
            <label for="rating">Rating:</label>
            <select name="rating" id="rating">
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
            </select>
            <br>
            <label for="description">Description:</label>
            <textarea name="description" id="description"></textarea>
            <br>
            <input type="submit" value="Submit Review">
            <a href="index.php" class="btn btn-warning">Return</a>
        </form>
    </div>
</body>
</html>
