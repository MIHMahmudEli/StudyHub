<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/config/config.php';

echo "<h2>Seeding 10 Dummy Students...</h2>";

$userModel = new User();
$db = Database::getInstance()->getConnection();

$names = [
    'Alice Johnson', 'Bob Smith', 'Charlie Brown', 'Diana Prince', 'Evan Wright',
    'Fiona Gallagher', 'George Michael', 'Hannah Montana', 'Ian Somerhalder', 'Jessica Jones'
];

$count = 0;

foreach ($names as $i => $name) {
    $email = strtolower(str_replace(' ', '.', $name)) . "@example.com";
    $password = "password123"; // Simple password for testing
    
    // Check if exists
    if ($userModel->findByEmail($email)) {
        echo "<p style='color:orange'>User $email already exists. Skipping.</p>";
        continue;
    }

    if ($userModel->create($name, $email, $password)) {
        echo "<p style='color:green'>Created user: $name ($email)</p>";
        
        // Let's give them some random points for leaderboard testing
        $points = rand(10, 500);
        $stmt = $db->prepare("UPDATE users SET points = ? WHERE email = ?");
        $stmt->bind_param("is", $points, $email);
        $stmt->execute();
        
        $count++;
    } else {
        echo "<p style='color:red'>Failed to create user: $name</p>";
    }
}

echo "<h3>Done! Created $count new students.</h3>";
echo "<p><a href='index.php'>Go to Home</a></p>";
