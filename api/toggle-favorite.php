<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

// Check if user is logged in
if (!isAuthenticated()) {
    jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$game_id = intval($input['game_id'] ?? 0);
$action = $input['action'] ?? '';

if (!$game_id || !in_array($action, ['add', 'remove'])) {
    jsonResponse(['success' => false, 'message' => 'Invalid parameters']);
}

$user_id = $_SESSION['user_id'];

try {
    if ($action === 'add') {
        $result = addToFavorites($user_id, $game_id);
        if ($result) {
            logActivity($user_id, 'add_favorite', "Added game {$game_id} to favorites");
            jsonResponse(['success' => true, 'message' => 'Added to favorites']);
        } else {
            jsonResponse(['success' => false, 'message' => 'Failed to add to favorites']);
        }
    } else {
        $result = removeFromFavorites($user_id, $game_id);
        if ($result) {
            logActivity($user_id, 'remove_favorite', "Removed game {$game_id} from favorites");
            jsonResponse(['success' => true, 'message' => 'Removed from favorites']);
        } else {
            jsonResponse(['success' => false, 'message' => 'Failed to remove from favorites']);
        }
    }
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error'], 500);
}
?>