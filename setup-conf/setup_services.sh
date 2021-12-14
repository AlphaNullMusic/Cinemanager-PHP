#!/bin/bash
# RUN AS ROOT!

# Copy nginx configs
cp nginx/default_server /etc/nginx/sites-available/.
cp nginx/shoreline.nz /etc/nginx/sites-available/.
ln -s /etc/nginx/sites-available/default_server /etc/nginx/sites-enabled/default_server
ln -s /etc/nginx/sites-available/shoreline.nz /etc/nginx/sites-enabled/shoreline.nz
systemctl restart nginx

# Copy apache2 configs
a2dissite 000-default
cp apache2/apache2.conf /etc/apache2/.
cp apache2/ports.conf /etc/apache2/.
systemctl reload apache2

cp apache2/shoreline.conf /etc/apache2/sites-available/.
a2ensite shoreline.conf

# Copy systemd services
cp systemd/* /etc/systemd/system/.
systemctl daemon-reload

systemctl enable bounces.timer
systemctl enable newsletter.timer
systemctl daemon-reload
