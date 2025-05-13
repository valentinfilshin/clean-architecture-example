# Пример чистой архитектуры для простого приложения
Сервис работает на php версии >=8.2. 

Для разворачивания проекта необходимо установить Docker и Docker compose. Затем в корне проекта выполнить
```shell
docker compose up -d
```

Далее необходимо перейти в контейнер с PHP и выполнить команду
```shell
docker exec -it php-hw12 sh 
```

И установить зависимости composer
```shell
composer install
```

После этого в браузере можно проверить работоспособность проекта
```
http://localhost:7777
```

Для запуска тестов
Codeception
```shell
./vendor/bin/codecept run --coverage --coverage-html
```

PhpUnit
```shell
./vendor/bin/phpunit --coverage-html var/coverage
```