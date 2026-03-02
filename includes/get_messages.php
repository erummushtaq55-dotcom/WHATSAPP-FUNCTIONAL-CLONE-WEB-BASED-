<?php
require_once '../config/db.php';
require_once 'functions.php';

if (!isLoggedIn())
    exit();

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'];

$stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
$stmt->execute([$sender_id, $receiver_id, $receiver_id, $sender_id]);
$messages = $stmt->fetchAll();

foreach ($messages as $msg) {
    $type = ($msg['sender_id'] == $sender_id) ? 'sent' : 'received';
    $time = date('h:i A', strtotime($msg['created_at']));
    $status = ($type == 'sent') ? '<i class="fas fa-check-double" style="color: #34b7f1; font-size: 10px; margin-left: 5px;"></i>' : '';

    echo '
    <div class="message message-' . $type . '">
        ' . htmlspecialchars($msg['message']) . '
        <span class="message-time">' . $time . $status . '</span>
    </div>';
}
?>
