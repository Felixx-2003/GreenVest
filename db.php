<?php
$host = "dpg-cv8hbljqf0us73b8b4g0-a"; // Your host
$dbname = "greenvest_db"; // Your database name
$user = "greenvest_db_user"; // Your username
$password = "fuCvjWCBy76oUBxWaKeGUuilWyCZ679W"; // Replace with the actual password
$port = "5432"; // Default PostgreSQL port

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    echo "Connected to PostgreSQL successfully!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
