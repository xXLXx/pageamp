#!/bin/bash

docker build -t pageamp .

docker run --rm -it -e "PAYLOAD_FILE=payload.example.json" pageamp php Worker.php