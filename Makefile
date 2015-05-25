dep:
	(rm -rf ./_dist_/ && cd ./deploy && grunt deploy)
refresh:
	(git pull && cd ./deploy && bower install && npm install && grunt less)
git-save:
	(git add . && git add . -u && git commit -m "$(msg)" && git push)