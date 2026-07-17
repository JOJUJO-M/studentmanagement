#!/bin/bash
# deploy_ubuntu.sh
# Run on a fresh Ubuntu 22.04+ server (as root or sudo user)

set -euo pipefail

APP_DIR=/var/www/studentmanagement
REPO_URL=https://github.com/JOJUJO-M/studentmanagement.git
DB_NAME=school_db
DB_USER=root
DB_PASS=""  # update if using a password

# Update and install packages
apt update
apt -y upgrade
apt -y install apache2 mysql-client mariadb-server php php-mysql libapache2-mod-php git unzip curl certbot python3-certbot-apache

# Enable Apache modules
a2enmod rewrite headers
systemctl restart apache2

# Create app directory and clone repo
mkdir -p ${APP_DIR}
chown -R $USER:$USER ${APP_DIR}
rm -rf ${APP_DIR}/*

git clone ${REPO_URL} ${APP_DIR}

# Set permissions
chown -R www-data:www-data ${APP_DIR}
find ${APP_DIR} -type d -exec chmod 755 {} \;
find ${APP_DIR} -type f -exec chmod 644 {} \;

# Configure Apache virtual host
cat > /etc/apache2/sites-available/studentmanagement.conf <<'EOF'
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/studentmanagement/public
    ServerName YOUR_DOMAIN_OR_IP

    <Directory /var/www/studentmanagement/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/studentmanagement_error.log
    CustomLog ${APACHE_LOG_DIR}/studentmanagement_access.log combined
</VirtualHost>
EOF

a2ensite studentmanagement.conf
systemctl reload apache2

# Import database (if database.sql present)
if [ -f "${APP_DIR}/database.sql" ]; then
    echo "Importing database..."
    mysql -u ${DB_USER} ${DB_PASS:+-p$DB_PASS} -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    mysql -u ${DB_USER} ${DB_PASS:+-p$DB_PASS} ${DB_NAME} < "${APP_DIR}/database.sql"
fi

# Update BASE_URL in config/database.php if necessary (example sets to root path)
# You may want to edit config/database.php to set correct DB credentials and BASE_URL

# Suggest setting up SSL
echo "Deployment complete. Edit /etc/apache2/sites-available/studentmanagement.conf to set ServerName, then run certbot for SSL:"
echo "  sudo certbot --apache -d YOUR_DOMAIN"

echo "Application deployed to ${APP_DIR}."
