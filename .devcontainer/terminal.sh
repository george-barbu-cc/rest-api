#!/bin/bash

# Export env vars
export $(grep -v '^#' .env | xargs)

CONTAINER_NAME="${APP_NAME}_web"

docker exec -it ${CONTAINER_NAME} bash
