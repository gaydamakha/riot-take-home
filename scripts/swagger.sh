#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
cd "$DIR" || exit 1

../vendor/bin/openapi \
    --output ../public/doc/swagger.yaml ../src
