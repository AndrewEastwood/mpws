#!/bin/bash
DBUSER=root
DBPWD=1111
DB=mpws_light
echo importing $1
if [ "$1" ]
then
    mysql -u$DBUSER -p$DBPWD $DB < $1
fi