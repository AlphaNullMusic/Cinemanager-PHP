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



# Finish script
echo "[INFO]: Script finished."
exit 0
