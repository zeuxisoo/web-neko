# Web Neko

A toy project for save ideas, bookmark and some activities

## Installation

Download composer

    make composer

Download vendor

    make vendor

Create environment, generate keys and edit

    cp .env.example .env

    php artisan key:generate
    php artisan jwt:generate

    edit .env

Migrate database

    make database

Build assets

    npm install
    make prod-assets

## Development

Start server

    make server

Watch assets

    make hot-assets

Seed database

    php artisan db:seed
