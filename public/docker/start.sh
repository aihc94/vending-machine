#!/bin/sh

if [ ! -d "vendor" ]; then
  echo "Installing dependencies..."
  composer install
fi

php-fpm -F