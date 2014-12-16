#!/bin/sh
echo "generating db dump"

DBPWD=1111
DBUSER=root
DBNAME=mpws_light
DBHOST=localhost

mysqldump -u $DBUSER -p$DBPWD --no-data --routines --add-drop-database -h $DBHOST --databases $DBNAME > ./schema/clean/${DBNAME}_full_schema.sql
mysqldump -u $DBUSER -p$DBPWD --no-data --no-create-db --routines -h $DBHOST $DBNAME > ./schema/clean/schema.sql
sed -E 's/DEFINER=`[^`]+`@`[^`]+`/DEFINER=CURRENT_USER/g' ./schema/clean/schema.sql > ./schema/clean/schema_portable.sql
sed -E 's/ AUTO_INCREMENT=[0-9]*\b//' ./schema/clean/schema_portable.sql > ./schema/clean/schema.sql
rm ./schema/clean/schema_portable.sql
mysqldump -u $DBUSER -p$DBPWD --routines --add-drop-database -h $DBHOST --databases $DBNAME > ./schema/clean/${DBNAME}_full_test.sql