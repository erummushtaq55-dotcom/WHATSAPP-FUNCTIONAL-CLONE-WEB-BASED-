<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = "All fields are required.";
    } else {
        try {
            // Check if email or phone already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
            $stmt->execute([$email, $phone]);
            if ($stmt->fetch()) {
                $error = "Email or Phone number is already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Preparing robust insertion
                $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, status, avatar, is_online) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $default_status = 'Hey there! I am using WhatsApp.';
                $default_avatar = 'default.png';
                $online_status = 0;

                if ($stmt->execute([$name, $email, $phone, $hashed_password, $default_status, $default_avatar, $online_status])) {
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['user_name'] = $name;
                    redirect('dashboard.php');
                } else {
                    $error = "Failed to create account. Please try again.";
                }
            }
        } catch (PDOException $e) {
            // Catch structural errors specifically
            $error = "Database Connection Error: " . $e->getMessage();
        } catch (Exception $e) {
            $error = "System Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join WhatsApp Clone | Secure Signup</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <i class="fab fa-whatsapp" style="font-size: 3rem; color: #25D366; margin-bottom: 15px;"></i>
            <h2>Join WhatsApp Clone</h2>
            <p>Connect with your friends today</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"
                style="background: rgba(255, 0, 0, 0.1); color: #ff4d4d; border: 1px solid #ff4d4d; border-radius: 8px; padding: 12px; margin-bottom: 20px; font-weight: 500; text-align: center; font-size: 0.9rem;">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" id="name" placeholder="John Doe" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" placeholder="john@example.com" required>
                </div>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <div class="input-wrapper">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone" id="phone" placeholder="+92 300 0000000" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Create a strong password"
                        required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"
                style="width: 100%; border-radius: 10px; margin-top: 15px; padding: 12px; font-weight: 600; background-color: #25D366; border: none; color: white; cursor: pointer;">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Create Account
            </button>
        </form>

        <div class="auth-footer"
            style="margin-top: 25px; text-align: center; border-top: 1px solid #eee; padding-top: 15px;">
            <p>Already have an account? <a href="login.php"
                    style="color: #25D366; text-decoration: none; font-weight: 600;">Log In</a></p>
        </div>
    </div>
</body>

</html>
