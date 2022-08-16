# Cinemanager
A custom-built CMS for cinema websites.

# Download software
- Login as root: `su -`
- Navigate to website root: `cd /var/www/`
- Download repo: `gh clone repo AlphaNullMusic/Cinemanager-PHP`
- Rename folder: `mv Cinemanager-PHP/ Cinemanager/`
- Create a user: `adduser cinemanager`
- Add user to www-data group: `usermod -a -G www-data cinemanager`
- Own everything in the folder: `chown -R www-data:www-data Cinemanager/`

- Setup NGINX as front-facing web server and reverse proxy to Apache2:
  - `https://www.digitalocean.com/community/tutorials/how-to-configure-nginx-as-a-web-server-and-reverse-proxy-for-apache-on-one-ubuntu-20-04-server`


- Copy apache configuration: `cp /var/www/Cinemanager/apache2-conf/* /etc/apache2/sites-available/`
- https://www.digitalocean.com/community/tutorials/how-to-run-multiple-php-versions-on-one-server-using-apache-and-php-fpm-on-ubuntu-18-04
- 
- Rename apache conf

# Install Apache2 and PHP-FPM
- `cd ~`
- `apt install apache2 php-fpm`
- `wget https://mirrors.edge.kernel.org/ubuntu/pool/multiverse/liba/libapache-mod-fastcgi/libapache2-mod-fastcgi_2.4.7~0910052141-1.2_amd64.deb`
- `dpkg -i libapache2-mod-fastcgi_2.4.7~0910052141-1.2_amd64.deb`

# Get PHP 7.2
- `apt-get install software-properties-common -y`
- `apt-get update -y`
- `apt-get install php7.2 php7.2-fpm php7.2-mysql php7.2-xml php7.2-gd libapache2-mod-php7.2 -y`
- `systemctl start php7.2-fpm`
- `a2enmod actions fcgid alias proxy_fcgi`
- `systemctl restart apache2`
- `a2enmod actions`
- `mv /etc/apache2/mods-enabled/fastcgi.conf /etc/apache2/mods-enabled/fastcgi.conf.default`
- `nano /etc/apache2/mods-enabled/fastcgi.conf`
  - Put the following:
  ```
   <IfModule mod_fastcgi.c>
    AddHandler fastcgi-script .fcgi
    FastCgiIpcDir /var/lib/apache2/fastcgi
    AddType application/x-httpd-fastphp .php
    Action application/x-httpd-fastphp /php-fcgi
    Alias /php-fcgi /usr/lib/cgi-bin/php-fcgi
    FastCgiExternalServer /usr/lib/cgi-bin/php-fcgi -socket /run/php/php7.2-fpm.sock -pass-header Authorization
    <Directory /usr/lib/cgi-bin>
      Require all granted
    </Directory>
  </IfModule>
  ```
- `systemctl reload apache2`

# Install nginx
- `apt install nginx`
- `rm /etc/nginx/sites-enabled/default`

# Run setup scripts
- `cd /var/www/Cinemanager/setup-conf`
- `./setup_tz.sh`
- `./setup_services.sh`

# Install mod_rpaf
- `cd ~`
- `apt install unzip build-essential apache2-dev`
- `wget https://github.com/gnif/mod_rpaf/archive/stable.zip`
- `unzip stable.zip`
- `cd mod_rpaf-stable`
- `make && make install`
- `echo 'LoadModule rpaf_module /usr/lib/apache2/modules/mod_rpaf.so' > /etc/apache2/mods-available/rpaf.load`
- `nano /etc/apache2/mods-available/rpaf.conf`
  - Add the following:
  ```
  <IfModule mod_rpaf.c>
    RPAF_Enable             On
    RPAF_Header             X-Real-Ip
    RPAF_ProxyIPs           <your_server_ip>
    RPAF_SetHostName        On
    RPAF_SetHTTPS           On
    RPAF_SetPort            On
  </IfModule>
  ```
- `a2enmod rpaf`
- `systemctl reload apache2`

# Allow through firewall
- `sudo ufw allow 22`
- `sudo ufw allow 80`
- `sudo ufw allow 433`
- `sudo ufw enable`

# Enable SSL
- `snap install --classic certbot`
- Run the following, selecting **No** for redirect
  - `certbot --agree-tos --no-eff-email --email roman@cinemanager.co.nz --nginx -d shorelinecinema.co.nz -d www.shorelinecinema.co.nz`
  - `certbot --agree-tos --no-eff-email --email roman@cinemanager.co.nz --nginx -d shoreline.nz -d www.shoreline.nz`
  - `certbot --agree-tos --no-eff-email --email roman@cinemanager.co.nz --nginx -d manage.shoreline.nz`
  - `certbot --agree-tos --no-eff-email --email roman@cinemanager.co.nz --nginx -d mysql.shoreline.nz`
  - `certbot --agree-tos --no-eff-email --email roman@cinemanager.co.nz --nginx -d posters.shoreline.nz`
- Create a crontab: `sudo crontab -e`
  - Add the following:
  ```
  0 0 * * * certbot renew
  ```
