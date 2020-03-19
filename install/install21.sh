#!/bin/sh

#export LANGUAGE=en_US.UTF-8
#export LC_ALL=en_US.UTF-8
#export LANG=en_US.UTF-8
#export LC_CTYPE=en_US.UTF-8


#sudo apt-get install autoconf -y
#sudo apt-get install autotools-dev -y
apt-get install build-essential autoconf libpcap-dev automake libtool
git clone --recursive https://github.com/obgm/libcoap.git
cd libcoap
git checkout dtls
git submodule update --init --recursive
./autogen.sh
./configure --disable-documentation --disable-shared
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
sudo git clone --depth 1 --recursive -b dtls https://github.com/home-assistant/libcoap.git 
cd libcoap
./autogen.sh
./configure --disable-documentation --disable-shared --without-debug CFLAGS="-D COAP_DEBUG_FD=stderr"
sudo make install

sudo apt-get -y install python-pip
pip install pip3 --upgrade && pip install tqdm

cd ~/NetDetect/

git clone https://github.com/sandyjmacdonald/ikea-smartlight.git ~/NetDetect/ikea-smartlight