### Команды

Запустить контейнеры
```shell
docker-compose up -d
```

Установить зависимости
```shell
docker-compose run --rm composer install
```

Развернуть таблицы
```shell
docker-compose exec backend php yii migrate/up
```

Запустить очередь (отправка SMS)
```shell
docker-compose exec backend php yii queue/run
```
