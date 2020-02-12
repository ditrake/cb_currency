# Курсы ЦБ
Сервис для вытягивания курсов ЦБ на заданную дату
## Запуск
* Для запуска требюуется наличие файла .env.local

Пример:
```dotenv
APP_ENV=dev
APP_DEBUG=true
APP_SECRET=}$seCr3t}

POSTGRES_PASSWORD=database_password
POSTGRES_USER=database_user
POSTGRES_DB=database
PGDATA=/var/lib/postgresql/data/pgdata
DATABASE_URL=database
DATABASE_PORT=5432
``` 
* Команды
```bash
docker-compose build
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app doctrine:migrations:migrate
```
## Использование
```bash
#currency - Может быть опущен
#date - Может быть опущен. По умолчанию сегодняшняя дата
docker-compose exec app app:daily --date=d/m/Y --currency=CURENCY_CODE
```
# Для запусков тестов
```bashK
docker-compose exec app bin/phpunit
```