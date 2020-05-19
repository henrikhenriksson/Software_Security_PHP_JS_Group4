#!/bin/bash

if command -v docker &> /dev/null ; then  # Docker is installed
    echo "Docker is installed"
    if docker ps | grep -q webserver; then  # server is running
        echo "Server is running, stopping..."
        docker stop webserver
    fi

    echo "Starting server..."
    docker run -p '8000:80' -p '8080:443' --mount type=bind,src=/php/,dst=/var/www/html -d webserver

    if [[ $? -eq 0 ]] ; then
        echo "Server started successfully!"
    else
        echo "Error when starting server!"
    fi

else
    echo "Docker is not installed!"
fi


