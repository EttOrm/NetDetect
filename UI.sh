#!/bin/sh

chromium-browser 0.0.0.0:8000
cd /home/pi/NetDetect
php -S 0.0.0.0:8000
