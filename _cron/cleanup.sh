#!/bin/bash

# Run movie cleanup
/usr/bin/php /var/www/Cinemanager/_cron/clean_movies.php

echo "[OK]: Cleaned database items."
