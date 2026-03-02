<?php
session_start();

function redirect($path)
{
    header("Location: $path");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function checkLogin()
{
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

function getAvatar($avatar)
{
    if (!$avatar || $avatar == 'default.png') {
        return 'https://ui-avatars.com/api/?background=25D366&color=fff&name=User';
    }
    return 'uploads/' . $avatar;
}
?>
