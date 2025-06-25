#!/bin/bash
# Get the absolute path to the cron.php file
CRON_SCRIPT_PATH=$(realpath "$(dirname "$0")/cron.php")
# Create the cron job
CRON_JOB="*/5 * * * * /usr/bin/php $CRON_SCRIPT_PATH"
# Add the cron job to the user's crontab
(crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
echo "Cron job set up to run $CRON_SCRIPT_PATH every 5 minutes." 