rsync -av ./ ./demo/verify_age/ --exclude-from=./.web_package/build/excludes.txt
rsync -av ./js/ ./demo/verify_age/js/ --include=*.min.js --exclude=*
