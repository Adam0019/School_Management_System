<?php

require_once __DIR__ . "/Config/dbcon.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $stmt = $pdo->prepare("SELECT u_id FROM user_tbl WHERE verification_token = ? AND verified = 0");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $update = $pdo->prepare("UPDATE user_tbl SET verified = 1, verification_token = NULL WHERE u_id = ?");
            $update->execute([$user['u_id']]);
            
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Email Verified</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .success { color: green; }
                    a { color: blue; text-decoration: none; }
                </style>
            </head>
            <body>
                <h1 class="success">✓ Email Verified!</h1>
                <p>Your account has been verified successfully.</p>
                <p><a href="./login.php">Click here to login</a></p>
            </body>
            </html>';
        } else {
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Verification Failed</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .error { color: red; }
                </style>
            </head>
            <body>
                <h1 class="error">✗ Verification Failed</h1>
                <p>Invalid or expired verification link.</p>
            </body>
            </html>';
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "No token provided.";
}
?>