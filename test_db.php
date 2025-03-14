<?php
$host = "dpg-cv8hbljqf0us73b8b4g0-a.oregon-postgres.render.com"; // External hostname
$dbname = "greenvest_db"; // Database name
$user = "greenvest_db_user"; // Database username
$password = "fuCvjWCBy76oUBxWaKeGUuilWyCZ679W"; // Database password
$port = "5432"; // Default PostgreSQL port

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✅ Connected to PostgreSQL successfully!";
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
