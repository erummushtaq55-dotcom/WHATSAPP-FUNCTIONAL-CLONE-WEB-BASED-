<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Get current user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $status = sanitize($_POST['status']);

    // Handle Profile Photo Upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $img_name = $_FILES['avatar']['name'];
        $img_type = $_FILES['avatar']['type'];
        $tmp_name = $_FILES['avatar']['tmp_name'];

        $img_explode = explode('.', $img_name);
        $img_ext = end($img_explode);
        $extensions = ['png', 'jpeg', 'jpg'];

        if (in_array($img_ext, $extensions)) {
            $time = time();
            $new_img_name = $time . $img_name;
            if (move_uploaded_file($tmp_name, "uploads/" . $new_img_name)) {
                $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                $stmt->execute([$new_img_name, $user_id]);
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE users SET name = ?, status = ? WHERE id = ?");
    if ($stmt->execute([$name, $status, $user_id])) {
        $_SESSION['user_name'] = $name;
        $success = "Profile updated successfully!";
        // Refresh user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } else {
        $error = "Failed to update profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | WhatsApp Clone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body style="background-color: var(--background-color);">
    <div class="navbar">
        <div class="container">
            <div class="logo">
                <i class="fab fa-whatsapp"></i>
                <a href="dashboard.php" style="text-decoration: none; color: inherit;">WhatsApp Clone</a>
            </div>
            <nav>
                <a href="dashboard.php" class="btn btn-outline">Back to Chat</a>
            </nav>
        </div>
    </div>

    <div class="container" style="max-width: 600px; margin-top: 50px;">
        <div class="auth-card" style="max-width: 100%;">
            <div class="auth-header">
                <h2>Profile Settings</h2>
                <p>Manage your public profile information</p>
            </div>

            <?php if ($success): ?>
                <div
                    style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="<?php echo getAvatar($user['avatar']); ?>" alt="Profile"
                        style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-color);">
                    <div style="margin-top: 10px;">
                        <input type="file" name="avatar" id="avatar" style="display: none;">
                        <label for="avatar" class="btn btn-outline" style="padding: 5px 15px; font-size: 14px;">Change
                            Photo</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="status">About / Status</label>
                    <input type="text" name="status" id="status" value="<?php echo $user['status']; ?>">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" value="<?php echo $user['email']; ?>" disabled style="background: #f8f9fa;">
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width: 100%; border-radius: 10px; margin-top: 10px;">Save Changes</button>
            </form>

            <div style="text-align: center; margin-top: 30px;">
                <a href="logout.php" style="color: #dc3545; text-decoration: none; font-weight: 600;">Log Out</a>
            </div>
        </div>
    </div>
</body>

</html>
