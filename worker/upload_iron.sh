#!/bin/bash

# Automatically replace all localhost in .env, assuming you have same configuration as server's
sed -i '' 's/http:\/\/localhost/http:\/\/page-amp.com/g' .env 

zip -r pageamp.zip .

sed -i '' 's/http:\/\/page-amp.com/http:\/\/localhost/g' .env 

iron worker upload --zip pageamp.zip --name pageamp_status_worker iron/php php Worker.php