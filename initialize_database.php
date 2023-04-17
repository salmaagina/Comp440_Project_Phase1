<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phase1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully<br>";
} else {
  echo "Error creating database: " . $conn->error;
}

// Check if the products table already exists
$table_exists = false;
$result = $conn->query("SHOW TABLES LIKE 'products'");
if ($result->num_rows > 0) {
  $table_exists = true;
}

// If the products table exists, drop it
if ($table_exists) {
  if ($conn->query("DROP TABLE products") === TRUE) {
    //echo "Table products dropped successfully<br>";
  } else {
    //echo "Error dropping table products: " . $conn->error;
  }
}

// Define SQL statements to create the tables
$sql2 = "CREATE TABLE IF NOT EXISTS products (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) NOT NULL,
		description TEXT,
		price DECIMAL(10, 2),
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)";

// Execute SQL statements to create the tables
if ($conn->query($sql2) === TRUE) {
  //echo "Table products created successfully<br>";
} else {
  //echo "Error creating table products: " . $conn->error;
}

// Define SQL statements to insert data into the tables
$sql5 = "INSERT INTO products (name, description, price)
		VALUES ('Product 1', 'This is product 1', 10),
		       ('Product 2', 'This is product 2', 20),
		       ('Product 3', 'This is product 3', 30),
		       ('Product 4', 'This is product 4', 40),
		       ('Product 5', 'This is product 5', 50)";

// Execute SQL statements to insert data into the tables
if ($conn->query($sql5) === TRUE) {
  //echo "Data inserted successfully<br>";
} else {
  //echo "Error inserting data: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
