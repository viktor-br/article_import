# article_import

Import articles from sqlite storage into local elasticsearch


```shell
scripts/import sqlite:reset
```

```shell
scripts/import elasticsearch:clear
```

To test import with real sqlite and elasticsearch
```shell
scripts/import elasticsearch:import --limit=10 --n=20
```

Run tests
```shell
vendor/bin/phpunit --configuration phpunit.xml --coverage-text tests
```