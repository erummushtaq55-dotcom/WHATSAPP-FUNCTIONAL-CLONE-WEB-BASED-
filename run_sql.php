<?php
require_once 'config/db.php';

try {
    $sql = file_get_contents('database.sql');

    // Execute multiple queries
    $pdo->exec($sql);

    echo "<div style='font-family: sans-serif; padding: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;'>
            <h3>Success!</h3>
            <p>Database has been reset and updated successfully with all columns (including 'phone').</p>
            <p><strong>Note:</strong> Please delete this file (run_sql.php) for security.</p>
            <a href='signup.php' style='display: inline-block; background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px;'>Go to Sign Up</a>
          </div>";

} catch (PDOException $e) {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>
            <h3>Database Error</h3>
            <p>" . $e->getMessage() . "</p>
          </div>";
}
?>
