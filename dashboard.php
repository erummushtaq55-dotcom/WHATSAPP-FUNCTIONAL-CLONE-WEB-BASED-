<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Clone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="chat-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="chat-main">
            <div class="welcome-screen">
                <img src="https://web.whatsapp.com/img/intro-connection-light_c98cc75f2aa905314d74375a975d2cf2.jpg"
                    alt="WhatsApp Desktop">
                <h2>Keep your phone connected</h2>
                <p>WhatsApp Clone connects to your phone to sync messages. To reduce data usage, connect your phone to
                    Wi-Fi.</p>
                <div style="margin-top: 30px; font-size: 14px; color: #667781;">
                    <i class="fas fa-laptop"></i> WhatsApp Clone is available for Windows. <a href="#"
                        style="color: var(--primary-color);">Get it here</a>.
                </div>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>

</html>
