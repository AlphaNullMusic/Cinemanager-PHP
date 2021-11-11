#!/bin/sh

# Set owner of posters/ to www-data
chown www-data /var/www/Cinemanager/posters
if [ $? -eq 0 ]; then
    echo "[OK]: Successfully owned folder 'posters'."
else
    echo "[FAILED]: Can't own folder 'posters'."
fi

# Set permissions of posters/ to 777
chmod -R 777 /var/www/Cinemanager/posters
if [ $? -eq 0 ]; then
    echo "[OK]: Successfully set permissions on folder 'posters'."
else
    echo "[FAILED]: Can't set permissions on folder 'posters'."
fi

# Set owner of config.inc.php to www-data
chown www-data /var/www/Cinemanager/config.inc.php
if [ $? -eq 0 ]; then
    echo "[OK]: Successfully owned file 'config.inc.php'."
else
    echo "[FAILED]: Can't own file 'config.inc.php'."
fi

# Own all postes
chown www-data:www-data /var/www/Cinemanager/posters/*
if [ $? -eq 0 ]; then
    echo "[OK]: Successfully owned all posters."
else
    echo "[FAILED]: Can't own all posters."
fi

chmod 777 /var/www/Cinemanager/posters/*
if [ $? -eq 0 ]; then
    echo "[OK]: Successfully set permissions on all posters."
else
    echo "[FAILED]: Can't set permissions on all posters."
fi

# Finish script
echo "[INFO]: Script finished."
exit 0
