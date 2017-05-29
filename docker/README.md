# NSA Local Dev Docker

Requires Docker and Docker-Compose

## Use
### First time Start

1. Copy env-example to .env
2. Modify .env to reflect your current configuration
3. In the termial, navigate to the root of this repository.
4. Start containers: `docker-compose up --remove-orphans -d`
5. Import data to mysql (can be downloaded from dev3) `docker exec -i mysql mysql -uroot -ppassword nsa < nsaSmall.sql`


### Subsequent Starts
1. In the termial, navigate to the root of this repository.
2. Start containers: `docker-compose up --remove-orphans -d`

### Stopping
```
docker-compose down --remove-orphans
```

## Todo:

-Apache
--Prevent access to /cgi-bin