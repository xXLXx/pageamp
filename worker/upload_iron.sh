#!/bin/bash

zip -r pageamp.zip .

iron worker upload --zip pageamp.zip --name pageamp_status_worker iron/php php Worker.php