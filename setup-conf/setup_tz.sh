#!/bin/bash

mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u root -p mysql
