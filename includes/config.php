<?php
// DB credentials.
define('DB_HOST', 'localhost');
define('DB_PORT', '5432'); // Change this if your PostgreSQL is using a different port.
define('DB_USER', 'postgres'); // Change this to your PostgreSQL username.
define('DB_PASS', 'Anurag'); // Change this to your PostgreSQL password.
define('DB_NAME', 'smarthr');

// Establish database connection.
try {
    $dbh = new PDO("pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>
