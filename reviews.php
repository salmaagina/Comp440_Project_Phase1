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

<form method="post" action="submit-review.php">
  <label for="item_id">Select an item:</label>
  <select name="item_id" id="item_id">
    <option value="1">Item 1</option>
    <option value="2">Item 2</option>
    <option value="3">Item 3</option>
  </select>
  <br>
  <label for="rating">Rating:</label>
  <select name="rating" id="rating">
    <option value="excellent">Excellent</option>
    <option value="good">Good</option>
    <option value="fair">Fair</option>
    <option value="poor">Poor</option>
  </select>
  <br>
  <label for="description">Description:</label>
  <textarea name="description" id="description"></textarea>
  <br>
  <input type="submit" value="Submit Review">
  
  <!--Return button to go back to main page -->
  <a href="index.php" class="btn btn-warning">return</a>
</form>