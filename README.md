# A-commerce API



## Create dev/test environment:

Download project.
    
```
git clone git@bitbucket.org:2mm/neure.git
```

Init docker containers.

```
cd acommerce-api

docker-compose up --build -d
```
   
Install dependencies

```
docker-compose exec composer install --no-interaction
``` 
   
Run db migrations.

```
docker-compose exec app php bin/console doctrine:migrations:migrate --no-interaction
```   

Load test data

```
docker-compose exec app php bin/console doctrine:fixtures:load --no-interaction
```   

API authentication is disabled by default but you can enable it by changing: `- { path: ^/api, roles: IS_AUTHENTICATED_ANONYMOUSLY }` to `- { path: ^/api, roles: IS_AUTHENTICATED_FULLY }`

Credentials for demo API Authentication are:

    - user: demo
    - pass: demo

That's it! You are ready to go.

## Access

API: Api is mounted by default in `localhost:8015/api`.

DB: Database is already configured but if you need local access: 

    - host: localhost
    - port: 33061
    - user: acommerce
    - pass: acommerce





    