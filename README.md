## About
A simple rental agreement module.

## Setup

Start Docker:
```
docker-compose up --build -d
``` 

Setup:
``` 
docker exec -it php74 bash

# set PHP version (we need 7.4)
update-alternatives --config php

# configure
echo 'DATABASE_URL="mysql://root:develop@mysql:3306/example?serverVersion=5.5&charset=utf8mb4"' > .env.local

# create database 
php bin/console doctrine:database:create

# run migrations
php bin/console doctrine:migrations:migrate

# load fixtures
php bin/console doctrine:fixtures:load
```

## Use in browser
```
# login: landlord1@example.com
# pass: pass
# another login: landlord2@example.com
# another pass: pass  
http://example2.local/login
```

## Unit tests
```
./vendor/bin/phpunit
```