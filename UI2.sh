#!/bin/sh

chromium-browser http://localhost:8000
cd /home/pi/NetDetect
php -S 0.0.0.0:8000
