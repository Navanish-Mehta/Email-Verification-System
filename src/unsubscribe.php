<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = '';

// Function to send the unsubscription confirmation email
function sendUnsubscribeConfirmationEmail($email, $code) {
    $subject = 'Confirm Unsubscription';
    $message = '<p>To confirm unsubscription, use this code: <strong>' . $code . '</strong></p>';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <no-reply@example.com>' . "\r\n";
    mail($email, $subject, $message, $headers);
}

// Handle email submission for unsubscription
if (isset($_POST['unsubscribe_email'])) {
    $email = filter_var($_POST['unsubscribe_email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $code = generateVerificationCode();
        $_SESSION['unsubscribe_code'] = $code;
        $_SESSION['email_to_unsubscribe'] = $email;
        sendUnsubscribeConfirmationEmail($email, $code);
        $message = '<div class="success">A verification code has been sent to your email to confirm unsubscription.</div>';
    } else {
        $message = '<div class="error">Invalid email format.</div>';
    }
}

// Handle verification code submission for unsubscription
if (isset($_POST['unsubscribe_verification_code'])) {
    if (isset($_SESSION['unsubscribe_code']) && isset($_SESSION['email_to_unsubscribe'])) {
        $user_code = $_POST['unsubscribe_verification_code'];
        if ($user_code == $_SESSION['unsubscribe_code']) {
            unsubscribeEmail($_SESSION['email_to_unsubscribe']);
            $message = '<div class="success">You have been unsubscribed successfully.</div>';
            // Clear session variables
            unset($_SESSION['unsubscribe_code']);
            unset($_SESSION['email_to_unsubscribe']);
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
    <title>Unsubscribe</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; line-height: 1.6; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        input[type="email"], input[type="text"] { padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 3px; }
        button { padding: 10px; border: none; background: #d9534f; color: white; border-radius: 3px; cursor: pointer; }
        button:hover { background: #c9302c; }
        .message { padding: 10px; margin-bottom: 10px; border-radius: 3px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Unsubscribe from GitHub Timeline Updates</h2>

        <?php echo $message; ?>

        <form action="unsubscribe.php" method="post">
            <label for="unsubscribe_email">Email:</label>
            <input type="email" name="unsubscribe_email" required>
            <button type="submit" id="submit-unsubscribe">Unsubscribe</button>
        </form>

        <hr>

        <h2>Verify Unsubscription</h2>
        <form action="unsubscribe.php" method="post">
            <label for="unsubscribe_verification_code">Verification Code:</label>
            <input type="text" name="unsubscribe_verification_code" maxlength="6" required>
            <button type="submit" id="verify-unsubscribe">Verify</button>
        </form>
    </div>
</body>
</html> 