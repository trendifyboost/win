<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

function validatePassword($password) {
    return strlen($password) >= 6;
}

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function getGameCategories() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY sort_order");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function getGamesByCategory($category_id = null, $limit = 12) {
    global $pdo;
    try {
        if ($category_id) {
            $stmt = $pdo->prepare("SELECT * FROM games WHERE category_id = ? ORDER BY is_hot DESC, created_at DESC LIMIT ?");
            $stmt->execute([$category_id, $limit]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM games ORDER BY is_hot DESC, created_at DESC LIMIT ?");
            $stmt->execute([$limit]);
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function addToFavorites($user_id, $game_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT IGNORE INTO user_favorites (user_id, game_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $game_id]);
    } catch (PDOException $e) {
        return false;
    }
}

function removeFromFavorites($user_id, $game_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM user_favorites WHERE user_id = ? AND game_id = ?");
        return $stmt->execute([$user_id, $game_id]);
    } catch (PDOException $e) {
        return false;
    }
}

function getUserFavorites($user_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT g.* FROM games g 
            INNER JOIN user_favorites uf ON g.id = uf.game_id 
            WHERE uf.user_id = ? 
            ORDER BY uf.created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function logActivity($user_id, $action, $details = null) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO user_activity (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $action, $details]);
    } catch (PDOException $e) {
        return false;
    }
}

function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

function formatTime($time) {
    return date('g:i A', strtotime($time));
}
?>
