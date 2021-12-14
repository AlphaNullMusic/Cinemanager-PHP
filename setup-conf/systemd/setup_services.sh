#!/bin/bash

sudo cp /var/www/Cinemanager/setup-conf/systemd/* /etc/systemd/system/.
systemctl daemon-reload

systemctl enable bounces.timer
systemctl enable newsletter.timer
systemctl daemon-reload
