#!/bin/sh

sudo apt-get install autoconf -y
sudo apt-get install autotools-dev -y
apt-get install build-essential autoconf libpcap-dev
sudo git clone https://github.com/royhills/arp-scan.git
cd arp-scan 
autoreconf --install 
./configure 
sudo make install
sudo apt-get install ca-certificates apt-transport-https -y
wget -q https://packages.sury.org/php/apt.gpg -O- | sudo apt-key add -
echo "deb https://packages.sury.org/php/ stretch main" | sudo tee /etc/apt/sources.list.d/php7.list
sudo apt update -y
sudo apt install -y php7.4-common php7.4-fpm php7.4-cli php7.4-json php7.4-mysql php7.4-opcache php7.4-sqlite3 php7.4-readline php-pear

sudo apt-get -y install python-pip
sudo pip install tqdm
git clone https://github.com/sandyjmacdonald/ikea-smartlight