#!/bin/bash

# Map host user and group id as arguments to the Docker compose file
# They will be used to modify the www-data user in the PHP image to run with the same id
# as the local user so we can write both from a development environment _and_ running environment
# to the www-data directory. This is important so we can actually edit code while the pxmboard
# can write the config file and template cache.
DM_UID=$(id -u) DM_GID=$(id -g) docker-compose up