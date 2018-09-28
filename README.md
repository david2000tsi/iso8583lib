
# ISO8583 PHP Library

This is a basic implementation of ISO8583 for PHP language for generate and decode messages.

Support the 1987 and 1993 ISO format (fields info data available), the 2003 format is not available.

Use Example.php file for tests.
In command line (linux environment) run this command and analyse his output:

```
php Example.php
```

Run tests/* test file using phpunit to validate each message class:

```
cd tests/
phpunit Message0200Test.php
```

To run all phpunit tests:

```
cd tests/
phpunit .
```
