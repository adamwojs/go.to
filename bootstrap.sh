#!/usr/bin/env bash

# Instalujemy software
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password pass'
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password pass'
apt-get update
apt-get install -y \
  vim git curl \
  mysql-server-5.5 mysql-client \
  php5 php5-cli php5-common php5-mysql php5-gd php5-curl php5-mcrypt php5-xdebug \
  libapache2-mod-php5 apache2

# Instalacja composera
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
sudo chmod +x /usr/bin/composer

# Tworzy DocumentRoot aplikacji
if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant/web /var/www
  sudo a2enmod rewrite 2> /dev/null
fi

# Dodajemy konfiguracje vhosta aplikacji
if ! [ -L /etc/apache2/sites-available/application.conf ]; then
  ln -fs /vagrant/vhost.conf /etc/apache2/sites-available/application.conf
  sudo a2ensite application.conf
fi

# Konfiguracja PHP
sudo sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 8M/" /etc/php5/apache2/php.ini
sudo sed -i "s/short_open_tag = On/short_open_tag = Off/" /etc/php5/apache2/php.ini
sudo sed -i "s/;date.timezone =/date.timezone = Europe\/Warsaw/" /etc/php5/apache2/php.ini
sudo sed -i "s/memory_limit = 128M/memory_limit = 1024M/" /etc/php5/apache2/php.ini
sudo sed -i "s/_errors = Off/_errors = On/" /etc/php5/apache2/php.ini

sudo echo "xdebug.remote_enable = on" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.remote_connect_back = on" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.idekey = \"vagrant\"" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.remote_host = 33.33.33.1" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.remote_port = 9000" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.remote_autostart = 0" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.profiler_enable = 1" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.profiler_output_name = xdebug.out.%t" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.profiler_output_dir = /vagrant/app/logs/" >> /etc/php5/apache2/conf.d/xdebug.ini
sudo echo "xdebug.profiler_enable_trigger = 1" >> /etc/php5/apache2/conf.d/xdebug.ini

# Restartujemy apacha
sudo apache2ctl restart
