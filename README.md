# WEBT_25-26_05-CORE

WEBT-VT | CORE | 05 - Sessions and Cookies
### Setup
```bash
  docker compose up -d
  docker exec -it CORE5 bash
```
```bash
  composer install
```
### Using PHP CS Fixer via the command line

```bash
  docker exec -it CORE5 bash
  vendor/bin/php-cs-fixer fix
```