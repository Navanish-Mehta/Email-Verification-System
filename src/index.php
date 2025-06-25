<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = '';

// Handle email submission
if (isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $code = generateVerificationCode();
        $_SESSION['verification_code'] = $code;
        $_SESSION['email_to_verify'] = $email;
        sendVerificationEmail($email, $code);
        $message = '<div class="success">A verification code has been sent to your email.</div>';
    } else {
        $message = '<div class="error">Invalid email format.</div>';
    }
}

// Handle verification code submission
if (isset($_POST['verification_code'])) {
    if (isset($_SESSION['verification_code']) && isset($_SESSION['email_to_verify'])) {
        $user_code = $_POST['verification_code'];
        if ($user_code == $_SESSION['verification_code']) {
            registerEmail($_SESSION['email_to_verify']);
            $message = '<div class="success">Email verified successfully! You are now subscribed.</div>';
            // Clear session variables
            unset($_SESSION['verification_code']);
            unset($_SESSION['email_to_verify']);
        } else {
            $message = '<div class="error">Invalid verification code.</div>';
        }
    } else {
        $message = '<div class="error">Please submit your email first.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; line-height: 1.6; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        input[type="email"], input[type="text"] { padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 3px; }
        button { padding: 10px; border: none; background: #337ab7; color: white; border-radius: 3px; cursor: pointer; }
        button:hover { background: #286090; }
        .message { padding: 10px; margin-bottom: 10px; border-radius: 3px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register for GitHub Timeline Updates</h2>
        
        <?php echo $message; ?>

        <form action="index.php" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <button type="submit" id="submit-email">Submit</button>
        </form>

        <hr>

        <h2>Verify Your Email</h2>
        <form action="index.php" method="post">
            <label for="verification_code">Verification Code:</label>
            <input type="text" name="verification_code" maxlength="6" required>
            <button type="submit" id="submit-verification">Verify</button>
        </form>
    </div>
</body>
</html> 