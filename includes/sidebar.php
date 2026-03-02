<?php
// This is included inside the dashboard/chat layout
$current_user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$current_user_id]);
$current_user = $stmt->fetch();

// Get all users (except self)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id != ? ORDER BY is_online DESC, name ASC");
$stmt->execute([$current_user_id]);
$contacts = $stmt->fetchAll();
?>
<div class="sidebar">
    <div class="sidebar-header">
        <div class="user-profile">
            <img src="<?php echo getAvatar($current_user['avatar']); ?>" alt="Profile" class="avatar"
                onclick="location.href='profile.php'">
        </div>
        <div class="header-icons">
            <i class="fas fa-circle-notch"></i>
            <i class="fas fa-comment-alt"></i>
            <i class="fas fa-ellipsis-v" id="menu-toggle"></i>
            <div class="dropdown-menu" id="user-menu">
                <a href="profile.php">Profile</a>
                <a href="admin/index.php">Admin Panel</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="search-container">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="contact-search" placeholder="Search or start new chat">
        </div>
    </div>

    <div class="contacts-list" id="contacts-list">
        <?php foreach ($contacts as $contact): ?>
            <div class="contact-item <?php echo (isset($receiver_id) && $receiver_id == $contact['id']) ? 'active' : ''; ?>"
                onclick="window.location.href='chat.php?id=<?php echo $contact['id']; ?>'">
                <div class="contact-avatar">
                    <img src="<?php echo getAvatar($contact['avatar']); ?>" alt="Contact">
                    <?php if ($contact['is_online']): ?>
                        <span class="online-badge"></span>
                    <?php endif; ?>
                </div>
                <div class="contact-info">
                    <div class="contact-top">
                        <span class="contact-name">
                            <?php echo $contact['name']; ?>
                        </span>
                        <span class="last-msg-time"></span>
                    </div>
                    <div class="contact-bottom">
                        <span class="last-msg-preview">
                            <?php echo $contact['status']; ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
