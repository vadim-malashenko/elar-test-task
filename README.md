
```shell
mkdir -p vendor/elar && cd vendor/elar
symfony new shop --webapp && cd shop

composer remove symfony/stimulus-bundle
composer remove symfony/ux-turbo
composer require symfony/security-bundle
composer require symfony/validator
composer require orm-fixtures --dev
composer require --dev dama/doctrine-test-bundle

symfony console make:user
symfony console make:entity Product
symfony console make:entity Order

symfony console make:form OrderType
symfony console make:security:form-login

symfony console make:controller Login
symfony console make:controller OrderController

symfony console make:migration
symfony console doctrine:migrations:migrate

symfony console make:fixtures
symfony console doctrine:fixtures:load

symfony console make:test
symfony console --env=test doctrine:database:create
symfony console --env=test doctrine:schema:create
symfony console --env=test doctrine:fixtures:load
```