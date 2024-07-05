#!/bin/bash

# Allows to run composer commands inside the web container instead of installing it locally

docker container exec riot-take-home-web composer "$@" --working-dir=/app
