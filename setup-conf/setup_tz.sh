#!/bin/bash


timedatectl set-ntp yes
timedatectl set-timezone Pacific/Auckland

systemctl restart mariadb

mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u root -p mysql
