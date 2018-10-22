
# ISO8583 PHP Library

This is a basic implementation of ISO8583 for PHP language for generate and decode messages.

Support the 1987 and 1993 ISO format (fields info data available), the 2003 format is not available.

Before anything, in command line (linux environment), run composer dump-autoload command to generate autoload files:

```
composer dump-autoload
```

Use example/Example.php file for tests (run and analyse his output):

```
cd <project_path>/example/
php Example.php
```

Run tests/* test file using phpunit to validate each message class:

```
cd <project_path>/tests/
phpunit Message0100Test.php
phpunit Message0200Test.php
...
```

To run all phpunit tests:

```
cd <project_path>/tests/
phpunit .
```
