#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
cd "$DIR" || exit 1

export APPLICATION_NAME="riot-take-home"
cp ../.env.srv ../.env
docker-compose down --remove-orphans
docker-compose up --build --detach
echo "Launched $APPLICATION_NAME"
