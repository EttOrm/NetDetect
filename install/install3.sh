#!/bin/sh

#export LANGUAGE=en_US.UTF-8
#export LC_ALL=en_US.UTF-8
#export LANG=en_US.UTF-8
#export LC_CTYPE=en_US.UTF-8

cd /

#sudo apt-get install autoconf -y
#sudo apt-get install autotools-dev -y

sudo apt-get install autoconf automake libtool

git clone https://github.com/obgm/libcoap.git
cd libcoap
git checkout origin/dtls
git checkout -b dtls
git submodule update --init ext/tinydtls
cd ext/tinydtls
autoreconf
cd ../../
./autogen.sh
./configure --disable-shared --disable-documentation --without-debug CFLAGS="-D COAP_DEBUG_FD=stderr"
make
sudo make install

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

cd /home/pi/libcoap

npm install node-tradfri --save
npm audit fix

cd /home/pi/

npm install node-tradfri --save
npm audit fix

cd ~/NetDetect/