#!/bin/bash
DBUSER=root
DBPWD=1111
mysql -u$DBUSER -p$DBPWD < schema/clean/mpws_light_full_test.sql