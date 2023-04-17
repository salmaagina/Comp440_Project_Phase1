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
</form>