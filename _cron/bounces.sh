#!/bin/bash

# Run bounces.php
/usr/bin/php7.2 /var/www/Cinemanager/_cron/bounces.php

# Run poster perms
/bin/bash /var/www/Cinemanager/perms.sh

