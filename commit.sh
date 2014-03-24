#!/bin/sh

echo "commiting changes"
echo "========================"
echo "git pull"
echo "git add ."
echo "git add . -u"
echo "git commit -m \"$1\""
echo "git push"
echo "git status"

git pull && git add . && git add . -u && git commit -m \"$1\" && git push && git status