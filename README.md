# DoctrineTestModule

Laminas module that provides features that help you run your Laminas App's testsuite more efficiently with isolated tests.

> Based on Symfony's [doctrine-test-bundle](https://github.com/dmaicher/doctrine-test-bundle).

It provides a `StaticDriver` that will wrap your originally configured `Driver` class (like `DBAL\Driver\PDOMysql\Driver`) and keeps a database connection statically in the current php process.

With the help of a PHPUnit extension class it will begin a transaction before every testcase and roll it back again after the test finished for all configured DBAL connections. This results in a performance boost as there is no need to rebuild the schema, import a backup SQL dump or re-insert fixtures before every testcase. As long as you avoid issuing DDL queries that might result in implicit transaction commits (Like `ALTER TABLE`, `DROP TABLE` etc; see https://wiki.postgresql.org/wiki/Transactional_DDL_in_PostgreSQL:_A_Competitive_Analysis) your tests will be isolated and all see the same database state.

### How to install and use this Module?

1. install via composer

    ```sh
    composer require --dev facile-it/doctrine-test-module
    ```

2. Enable the module for your test environment in your `config/application.php`

    ```php
    <?php
    
    return [
        'modules' => [
           'Facile\DoctrineTestModule',
        ],
    ];
    ```

#### Using the Module with PHPUnit

1. Add the Extension to your PHPUnit XML config

    - PHPUnit 9:

        ```xml
        <phpunit>
            ...
            <extensions>
                <extension class="Facile\DoctrineTestModule\PHPUnit\PHPUnitExtension" />
            </extensions>
        </phpunit>
        ```
    - PHPUnit 10+:

       ```xml
       <phpunit>
           ...
           <extensions>
               <bootstrap class="Facile\DoctrineTestModule\PHPUnit\PHPUnitExtension" />
           </extensions>
       </phpunit>
       ```

2. Make sure you also have `phpunit/phpunit` available as a `dev` dependency (**versions 9 and 10 are supported with the built-in extension**) to run your tests.

3. That's it! From now on whatever changes you do to the database within each single testcase are automatically rolled back for you :blush:

### Configuration

The module exposes a configuration that looks like this by default:

```php
use Facile\DoctrineTestModule\ConfigProvider;

return [
    ConfigProvider::CONFIGURATION: [
        'enable_static_connection' => true,
    ]
];
```

Setting `enable_static_connection: true` means it will enable it for all configured doctrine dbal connections.

You can selectively only enable it for some connections if required:

```php
use Facile\DoctrineTestModule\ConfigProvider;

return [
    ConfigProvider::CONFIGURATION: [
        'enable_static_connection' => [
            'orm_default' => true,
        ],
    ]
];
```
