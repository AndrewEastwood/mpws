#!/bin/bash
sudo apt-get install nodejs
sudo npm install grunt -g
sudo npm install bower -g
importdb.sh ../schema/clean/mpws_light_full_test.sql
setupdeps.sh