#!/bin/bash

# Automatically replace all localhost in .env, assuming you have same configuration as server's
sed -i '' 's/http:\/\/localhost/http:\/\/109.237.25.248/g' .env 

zip -r pageamp.zip .

sed -i '' 's/http:\/\/109.237.25.248/http:\/\/localhost/g' .env 

iron worker upload --zip pageamp.zip --name pageamp_status_worker iron/php php Worker.php