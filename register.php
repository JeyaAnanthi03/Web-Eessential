<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "pass";
$database = "food_app";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $foodPreference = isset($_POST['foodPreference']) ? trim($_POST['foodPreference']) : '';

    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address) || empty($foodPreference) || $age <= 0) {
        die("Error: All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    if (!preg_match("/^\d{10}$/", $phone)) {
        die("Error: Phone number must be exactly 10 digits.");
    }

    if (strlen($password) < 6) {
        die("Error: Password must be at least 6 characters.");
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (name, age, email, password, phone, address, food_preference) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error: SQL Prepare Failed - " . $conn->error);
    }

    $stmt->bind_param("sisssss", $name, $age, $email, $hashed_password, $phone, $address, $foodPreference);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: Invalid request method.";
}
?>
