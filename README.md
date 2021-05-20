`docker-compose build`
`docker-compose up`
`docker exec -it form-task_fpm_1 bash`
Copy `.env.dist` to `.env`
`composer install`
`symfony console messenger:setup-transports`
`APP_ENV=test symfony console messenger:setup-transports`

`cd spa`
Copy `.env.dist` to `.env`
Copy `phpunit.xml.dist` to `.phpunit.xml`
`yarn install && yarn encore dev`

open `http://localhost:10303/`

To send email run
`symfony console messenger:consume async -v`
To stop worker run
`symfony console messenger:stop-workers`
To debug worker 
```shell
symfony console messenger:failed:show
symfony console messenger:failed:retry
symfony console messenger:failed:remove
```

To run tests execute
```shell
rm -rf /tmp/symfony-cache/ && APP_ENV=test symfony php bin/phpunit
```
