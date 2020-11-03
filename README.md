<p align="center">
    <h1 align="center">Agora Intranet</h1>
    <br>
</p>

Agora Intranet API based on [Yii2 Framework](https://yiiframework.com).


REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.0.0.


INSTALLATION of API
-------------------

#### You need to have `composer` installed.

If you do not have [Composer](http://getcomposer.org/), you need to install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).
 
1. Clone the project.
1. Go to the project root directory. 
1. Run `composer install`.
1. Copy `.env.example` into `.env` and adjust parameters (Leave the database parameters unchanged if you are going to setup with docker)

### Setup with php builtin server

1. Adjust database parameters in `.env`
1. Run `php create-database.php` to create the database.
1. Run `sh migrate` to create tables and insert initial data.
1. Run `php yii serve --port 8081` and open in browser [http://localhost:8081](http://localhost:8081)

### Setup with Docker

Make sure you have docker installed. Also, make sure you don't have Apache or Mysql running and ports 80 and 3306 are not busy.
1. Start the docker processing: `docker-compose up -d`
1. Run migrations: `docker-compose exec app sh migrate`

Open in the browser [http://agora-api.localhost](http://agora-api.localhost)

##### To run console commands
`docker-compose exec app php yii <controller>/<action>`

INSTALLATION of VUE.JS APP
--------------------------

You must have [Node.js](https://nodejs.org) installed.<br>

---

Go to the `vue` folder.<br>
Copy `.env.example` into `.env` and specify the API url in `VUE_APP_API_HOST`

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Run your tests
```
npm run test
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](http://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2_basic_tests` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.

MAILER

### Run mailer migration
`php yii migrate --migrationPath="vendor/intermundia/yii2-mailer/migrations"`

### SMTP configuration
```
   SMTP_HOST =
   SMTP_USERNAME =
   SMTP_PASSWORD =
   SMTP_PORT =
   SMTP_ENCRYPTION =
```