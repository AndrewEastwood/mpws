#!/bin/sh
echo "generating db dump"

DBPWD=1111
DBUSER=root
DBNAME=mpws_light
DBHOST=localhost
PRJECT_ROOT=../
DBSCRIPTS=$PRJECT_ROOT/schema/clean

mysqldump -u $DBUSER -p$DBPWD --no-data --routines --add-drop-database -h $DBHOST --databases $DBNAME > $DBSCRIPTS/${DBNAME}_full_schema.sql
mysqldump -u $DBUSER -p$DBPWD --no-data --no-create-db --routines -h $DBHOST $DBNAME > $DBSCRIPTS/schema.sql
sed -E 's/DEFINER=`[^`]+`@`[^`]+`/DEFINER=CURRENT_USER/g' $DBSCRIPTS/schema.sql > $DBSCRIPTS/schema_portable.sql
sed -E 's/ AUTO_INCREMENT=[0-9]*\b//' $DBSCRIPTS/schema_portable.sql > $DBSCRIPTS/schema.sql
rm $DBSCRIPTS/schema_portable.sql
mysqldump -u $DBUSER -p$DBPWD --routines --add-drop-database -h $DBHOST --databases $DBNAME > $DBSCRIPTS/${DBNAME}_full_test.sql