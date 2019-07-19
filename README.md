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
```bash
docker-compose up -d --build
```
```bash
docker-compose exec php-cli zsh
```
```bash
composer install
```
_For developers:_
```bash
bin/console doctrine:fixtures:load
```
```bash
exit
```
### After installation:
_Click on the link in the browser_
_http://0.0.0.0:8001_