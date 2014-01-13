rsync -av ./ ./demo/verify_age/ --exclude-from=./build/excludes.txt
rsync -av ./js/ ./demo/verify_age/js/ --include=*.min.js --exclude=*
