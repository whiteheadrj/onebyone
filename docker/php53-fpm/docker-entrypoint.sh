#!/bin/bash
set -e

for f in /docker-entrypoint-init.d/*.sh; do
    . "$f" >&2
done

exec php-fpm
