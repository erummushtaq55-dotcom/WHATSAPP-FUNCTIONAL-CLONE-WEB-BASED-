<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
checkLogin();

if (!isset($_GET['id'])) {
    redirect('dashboard.php');
}

$receiver_id = $_GET['id'];
$sender_id = $_SESSION['user_id'];

// Get receiver info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$receiver_id]);
$receiver = $stmt->fetch();

if (!$receiver) {
    redirect('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with
        <?php echo $receiver['name']; ?> | WhatsApp Clone
    </title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="chat-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="chat-main" id="chat-window">
            <div class="chat-header">
                <div class="receiver-avatar">
                    <img src="<?php echo getAvatar($receiver['avatar']); ?>" alt="">
                </div>
                <div class="receiver-info">
                    <h4>
                        <?php echo $receiver['name']; ?>
                    </h4>
                    <p>
                        <?php echo $receiver['is_online'] ? 'Online' : 'Last seen ' . date('h:i A', strtotime($receiver['last_seen'])); ?>
                    </p>
                </div>
                <div class="header-icons" style="margin-left: auto;">
                    <i class="fas fa-search"></i>
                    <i class="fas fa-paperclip"></i>
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>

            <div class="messages-area" id="messages-area">
                <!-- Messages will be loaded here via AJAX -->
            </div>

            <div class="chat-footer">
                <i class="far fa-smile"></i>
                <input type="text" id="message-input" class="message-input" placeholder="Type a message"
                    autocomplete="off">
                <i class="fas fa-paper-plane send-btn" id="send-btn"></i>
            </div>
        </div>
    </div>

    <script>
        const sender_id = <?php echo $sender_id; ?>;
        const receiver_id = <?php echo $receiver_id; ?>;
    </script>
    <script src="js/app.js"></script>
</body>

</html>
