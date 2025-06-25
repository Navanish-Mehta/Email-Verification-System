# GH-Timeline Email Subscription Service

This project is a PHP-based email verification system where users can register to receive GitHub timeline updates every 5 minutes. It includes features for email verification, unsubscription, and a CRON job for fetching and sending updates.

---

## 🚀 Features

- **Email Verification**: Users register with their email and verify it using a 6-digit code sent to them.
- **Subscription Management**: Verified emails are stored in a text file (`registered_emails.txt`).
- **Unsubscribe Mechanism**: Users can unsubscribe at any time through a link in the update emails, which also requires code verification.
- **Automated GitHub Updates**: A CRON job runs every 5 minutes to fetch the GitHub public timeline and email it to all subscribers.
- **Professional UI**: Simple, clean, and attractive user interface for registration and unsubscription pages.

---

## 🛠️ How to Run the Project

### A. On Windows (using XAMPP)

1. **Install XAMPP**  
   Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/index.html).

2. **Copy Project Files**  
   Place the `Email_Verification` folder inside `C:\xampp\htdocs\`.

3. **Configure Email Sending**  
   - Edit `C:\xampp\php\php.ini` and set:
     ```ini
     sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
     ```
   - Edit `C:\xampp\sendmail\sendmail.ini` and set up your Gmail SMTP with an [App Password](https://myaccount.google.com/apppasswords):
     ```ini
     [sendmail]
     smtp_server=smtp.gmail.com
     smtp_port=587
     smtp_ssl=tls
     error_logfile=error.log
     debug_logfile=debug.log
     auth_username=your-email@gmail.com
     auth_password=your-16-character-app-password
     force_sender=your-email@gmail.com
     ```

4. **Restart Apache**  
   Use the XAMPP Control Panel to restart Apache.

5. **Set File Permissions**  
   Make sure `src/registered_emails.txt` is writable by the web server.

6. **Access the App**  
   Go to [http://localhost/Email_Verification/src/index.php](http://localhost/Email_Verification/src/index.php) in your browser.

7. **Set Up Scheduled Task for CRON**  
   Use Windows Task Scheduler to run:
   ```
   C:\xampp\php\php.exe -f "D:\Email_Verification\src\cron.php"
   ```
   every 5 minutes. (See previous instructions for detailed steps.)

---

### B. On Linux

1. **Install Apache and PHP**  
   ```bash
   sudo apt update
   sudo apt install apache2 php
   ```

2. **Copy Project Files**  
   Place the project in `/var/www/html/Email_Verification`.

3. **Set File Permissions**  
   ```bash
   sudo chmod 666 /var/www/html/Email_Verification/src/registered_emails.txt
   ```

4. **Configure PHP Mail**  
   Make sure your server can send mail (install and configure `sendmail` or `postfix`).

5. **Access the App**  
   Go to `http://your-server-ip/Email_Verification/src/index.php`.

6. **Set Up CRON Job**  
   Run:
   ```bash
   chmod +x /var/www/html/Email_Verification/src/setup_cron.sh
   /var/www/html/Email_Verification/src/setup_cron.sh
   ```

---

### Troubleshooting

- If you do not receive emails, check your `sendmail.ini` and `php.ini` configuration.
- Check the permissions of `registered_emails.txt`.
- Check the PHP error log and `sendmail` logs for issues.
- Make sure you complete both steps of registration: submitting your email and then entering the verification code.
- If you are not receiving the verification email, fix your email configuration as described above.

---

## 📜 File Structure

- **`index.php`**: The main registration and verification page.
- **`unsubscribe.php`**: The page for handling user unsubscriptions.
- **`functions.php`**: Contains all the core logic and helper functions.
- **`cron.php`**: The script executed by the CRON job to send updates.
- **`setup_cron.sh`**: The script to automate the CRON job setup (Linux only).
- **`registered_emails.txt`**: A text file that acts as the database for storing subscriber emails.

---

## ⚙️ Core Functions (`functions.php`)

Here is an overview of the key functions that power this service:

```php
/**
 * Generates a random 6-digit numeric verification code.
 */
function generateVerificationCode();

/**
 * Registers a new email by appending it to registered_emails.txt.
 * Avoids adding duplicate entries.
 */
function registerEmail($email);

/**
 * Removes an email from registered_emails.txt.
 */
function unsubscribeEmail($email);

/**
 * Sends a verification email to the user with a 6-digit code.
 */
function sendVerificationEmail($email, $code);

/**
 * Fetches the latest data from the GitHub public timeline.
 */
function fetchGitHubTimeline();

/**
 * Formats the fetched GitHub data into an HTML table.
 * (Currently uses placeholder data).
 */
function formatGitHubData($data);

/**
 * Sends the formatted GitHub timeline updates to all subscribed users.
 * Appends an unsubscribe link to each email.
 */
function sendGitHubUpdatesToSubscribers();
``` 