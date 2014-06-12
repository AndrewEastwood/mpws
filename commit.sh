#!/bin/sh

echo "commiting changes"
echo "========================"
echo "git pull"
echo "git add ."
echo "git add . -u"
echo "git commit -m \"$1\""
echo "git push"
echo "git status"

git stash && git pull && git stash pop && mysqldump -u root -p1111 --no-data --routines --add-drop-database -h localhost --databases mpws_light > ./mpws/schema/clean/mpws_light_full_schema.sql && mysqldump -u root -p1111 --routines --add-drop-database -h localhost --databases mpws_light > ./mpws/schema/clean/mpws_light_full_test.sql && git add . && git add . -u && git commit -m "$1" && git push && git status
