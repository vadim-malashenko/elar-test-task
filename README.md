# Install

```shell
mkdir -p vendor/elar && cd vendor/elar
git clone git@github.com:vadim-malashenko/elar-test-task.git
cd elar-test-task

symfony console make:migration
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load

symfony serve
```

# Test

```shell
echo 'DATABASE_URL="sqlite:///%kernel.project_dir%/var/data_test.db"' > .env.test.local
symfony console --env=test doctrine:database:create
symfony console --env=test doctrine:schema:create
symfony console --env=test doctrine:fixtures:load

composer test
```

# Task

![Task](https://raw.githubusercontent.com/vadim-malashenko/elar-test-task/main/task.png)
