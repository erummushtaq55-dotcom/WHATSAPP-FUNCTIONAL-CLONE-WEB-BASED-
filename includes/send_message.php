<?php
require_once '../config/db.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = sanitize($_POST['message']);

    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        if ($stmt->execute([$sender_id, $receiver_id, $message])) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
        }
    }
}
?>
