# A-commerce API



## Setup dev/test environment:

Download project.
    
```
git clone https://github.com/ciscoeira/acommerce-api.git
```

Init docker containers.

```
cd acommerce-api

docker-compose up --build -d
```
   
Install dependencies

```
docker-compose exec app composer install --no-interaction
``` 
   
Run db migrations.

```
docker-compose exec app php bin/console doctrine:migrations:migrate --no-interaction
```   

Load test data

```
docker-compose exec app php bin/console doctrine:fixtures:load --no-interaction
```   

API authentication is enabled by default but you can disable it by changing: `- { path: ^/api, roles: IS_AUTHENTICATED_FULLY }` to `- { path: ^/api, roles: IS_AUTHENTICATED_ANONYMOUSLY }`

Credentials for demo API Authentication are:

    - user: demo
    - pass: demo

That's it! You are ready to go.

## Access

API: Go to [localhost:8015/api/doc](http://localhost:8015/api/doc) to see the doc and test it.

DB: Database is already configured but if you need local access: 

    - host: localhost
    - port: 33061
    - user: acommerce
    - pass: acommerce





    