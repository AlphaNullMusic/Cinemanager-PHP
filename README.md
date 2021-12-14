# Cinemanager

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
- Rename apache conf

# Setup Apache2
- `a2dissite 000-default`
- 
