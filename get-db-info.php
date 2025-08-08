<?php
// Script to display database connection information for deployment

echo "<h2>Database Connection Information</h2>";
echo "<p><strong>Note:</strong> Use this information to connect from your external hosting.</p>";

echo "<h3>Environment Variables:</h3>";
echo "<pre>";
echo "DATABASE_URL: " . ($_ENV['DATABASE_URL'] ?? 'Not set') . "\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'Not set') . "\n";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? 'Not set') . "\n";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'Not set') . "\n";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'Not set') . "\n";
echo "DB_PASSWORD: " . ($_ENV['DB_PASSWORD'] ?? 'Not set') . "\n";
echo "</pre>";

// Test current connection
try {
    require_once 'config/database.php';
    echo "<h3>Current Connection Status:</h3>";
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Show current tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Current Database Tables:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<h3>PHP Configuration Needed for External Host:</h3>";
echo "<pre>";
echo "// config/database.php for external hosting
$host = '" . ($_ENV['DB_HOST'] ?? 'your-replit-host') . "';
$port = '" . ($_ENV['DB_PORT'] ?? '3306') . "';
$dbname = '" . ($_ENV['DB_NAME'] ?? 'your-replit-database') . "';
$username = '" . ($_ENV['DB_USER'] ?? 'your-replit-user') . "';
$password = '" . ($_ENV['DB_PASSWORD'] ?? 'your-replit-password') . "';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}";

echo "</pre>";

// Test current connection
try {
    require_once 'config/database.php';
    echo "<h3>Current Connection Status:</h3>";
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Show current tables
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Current Database Tables:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<h3>PHP Configuration Needed for External Host:</h3>";
echo "<pre>";
echo "// config/database.php for external hosting
\$host = '" . ($_ENV['PGHOST'] ?? 'your-replit-host') . "';
\$port = '" . ($_ENV['PGPORT'] ?? '5432') . "';
\$dbname = '" . ($_ENV['PGDATABASE'] ?? 'your-replit-database') . "';
\$username = '" . ($_ENV['PGUSER'] ?? 'your-replit-user') . "';
\$password = '" . ($_ENV['PGPASSWORD'] ?? 'your-replit-password') . "';

try {
    \$pdo = new PDO(\"pgsql:host=\$host;port=\$port;dbname=\$dbname\", \$username, \$password);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException \$e) {
    die('Database connection failed: ' . \$e->getMessage());
}";
echo "</pre>";
?>