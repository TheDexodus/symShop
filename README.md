## Install
#### _Auto:_
```bash
sudo chmod +x ./install.sh && ./install.sh
```
_P.S. fixtures not loaded_
#### _Manual:_
```bash
cp .env.dist .env
```
###### _Edit the .env file as desired_
```bash
docker-compose up -d --build
```
```bash
docker-compose exec php-cli zsh
```
```bash
composer install
```
```bash
bin/console doctrine:migrations:migrate --no-interaction
```
######_For developers:_
```bash
bin/console doctrine:fixtures:load
```
######_Run tests:_
```bash
bin/phpunit
```
```bash
exit
```
### After installation:
_Click on the link in the browser_
_http://0.0.0.0:8001_