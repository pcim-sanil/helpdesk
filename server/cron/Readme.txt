# Create cron tab for nginx
$ sudo crontab -u nginx -e


* * * * * cd /var/www/html/helpdesk && /usr/bin/php artisan schedule:run >> /dev/null 2>&1


# Restart cron
$ sudo systemctl restart crond