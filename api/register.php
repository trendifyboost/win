<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$username = sanitizeInput($input['username'] ?? '');
$email = sanitizeInput($input['email'] ?? '');
$password = $input['password'] ?? '';
$confirm_password = $input['confirm_password'] ?? '';

// Validation
if (empty($username) || empty($email) || empty($password)) {
    jsonResponse(['success' => false, 'message' => 'All fields are required']);
}

if (!validateUsername($username)) {
    jsonResponse(['success' => false, 'message' => 'Username must be 3-20 characters and contain only letters, numbers, and underscores']);
}

if (!validateEmail($email)) {
    jsonResponse(['success' => false, 'message' => 'Invalid email format']);
}

if (!validatePassword($password)) {
    jsonResponse(['success' => false, 'message' => 'Password must be at least 6 characters long']);
}

if ($password !== $confirm_password) {
    jsonResponse(['success' => false, 'message' => 'Passwords do not match']);
}

$result = registerUser($username, $email, $password);

if ($result['success']) {
    logActivity($_SESSION['user_id'], 'register', 'User registered');
}

jsonResponse($result);
?>
