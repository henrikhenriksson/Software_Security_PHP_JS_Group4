#!/bin/bash

if command -v docker &> /dev/null ; then  # Docker is installed
    echo "Docker is installed"
    if docker ps | grep -q php_server; then  # server is running
        echo "Server is running, stopping..."
        docker stop php_server
    fi

    echo "Starting server..."
    docker run -p '8000:80' -p '8080:443' --mount type=bind,src=/php/,dst=/var/www/html -d php_server

    if [[ $? -eq 0 ]] ; then
        echo "Server started successfully!"
    else
        echo "Error when starting server!"
    fi

else
    echo "Docker is not installed!"
fi


