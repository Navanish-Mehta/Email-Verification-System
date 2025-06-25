<?php

function generateVerificationCode() {
    // Generate and return a 6-digit numeric code
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    // Save verified email to registered_emails.txt
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    // Remove email from registered_emails.txt
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (($key = array_search($email, $emails)) !== false) {
        unset($emails[$key]);
    }
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    // Send an email containing the verification code
    $subject = 'Your Verification Code';
    $message = '<p>Your verification code is: <strong>' . $code . '</strong></p>';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <no-reply@example.com>' . "\r\n";
    mail($email, $subject, $message, $headers);
}

function fetchGitHubTimeline() {
    // Fetch latest data from https://www.github.com/timeline
    $url = 'https://www.github.com/timeline';
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: PHP-Github-Timeline-Fetcher\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $data = file_get_contents($url, false, $context);
    return $data;
}

function formatGitHubData($data) {
    // Convert fetched data into formatted HTML
    // This is a placeholder as parsing the actual timeline is complex.
    // In a real-world scenario, you would parse the $data HTML.
    $html = '<h2>GitHub Timeline Updates</h2>';
    $html .= '<table border="1"><tr><th>Event</th><th>User</th></tr>';
    // Example data
    $html .= '<tr><td>Push</td><td>testuser</td></tr>';
    $html .= '<tr><td>Pull Request</td><td>anotheruser</td></tr>';
    $html .= '</table>';
    return $html;
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    // Send formatted GitHub timeline to registered users
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $github_data = fetchGitHubTimeline();
    $formatted_data = formatGitHubData($github_data);

    // Get the base URL for the unsubscribe link
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
    $unsubscribe_base_url = $protocol . $host . $path . '/unsubscribe.php';


    foreach ($emails as $email) {
        $subject = 'Latest GitHub Updates';
        $unsubscribe_link = $unsubscribe_base_url . '?email=' . urlencode($email);
        $message = $formatted_data . '<p><a href="' . $unsubscribe_link . '" id="unsubscribe-button">Unsubscribe</a></p>';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <no-reply@example.com>' . "\r\n";
        mail($email, $subject, $message, $headers);
    }
} 