# CashFly JPO

## Testing

This project uses PHPStan for static analysis, PHPUnit for unit testing, and Doctrine for integration tests.

### Running Static Analysis
To run PHPStan (level 8):
```bash
composer test:static
```

### Running Unit and Integration Tests
To run the PHPUnit test suite:
```bash
php bin/phpunit
```

To run only the unit tests:
```bash
php bin/phpunit tests/Unit/
```

To run only the integration tests:
```bash
php bin/phpunit tests/Integration/
```

### Note on Database Tests
Integration tests use an isolated SQLite database configured in `.env.test`. Before running integration tests for the first time or if schema changes occur, initialize the test schema:
```bash
php bin/console doctrine:database:create --env=test --if-not-exists
php bin/console doctrine:schema:update --force --env=test
```
