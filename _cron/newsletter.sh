#!/bin/bash

# Run cinemaemails.php
/usr/bin/php /var/www/Cinemanager/_cron/cinemaemails.php

# Copy log files
mkdir /var/www/logs && chown www-data:www-data /var/www/logs && chmod 755 /var/www/logs
if [ $? -eq 0 ]; then
	echo "[OK]: Successfully created folder 'var/www/logs'."
else
	echo "[INFO]: Folder '/var/www/logs' already exists."
fi

# Copy log files
cp /var/log/apache2/error-*.log /var/www/logs
if [ $? -eq 0 ]; then
	echo "[OK]: Successfully moved error-* log files to folder '/var/www/logs'."
else
	echo "[FAILED]: Can't move error-* log files to folder '/var/www/logs'."
fi

# Change ownership on log files
chown www-data:www-data /var/www/logs/error-*.log && chmod 755 /var/www/logs/error-*.log
if [ $? -eq 0 ]; then
        echo "[OK]: Successfully owned error-* log files in folder '/var/www/logs'."
else
        echo "[FAILED]: Can't own error-* log files in folder '/var/www/logs'."
fi
