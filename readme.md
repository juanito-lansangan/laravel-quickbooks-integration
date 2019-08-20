#QUICKBOOKS INTEGRATION
A sample code for quickbooks integration using laravel

##Prerequisites
1. Docker
2. Your favorite IDE or Text Editor
3. Quickbooks developer account
4. Quickbooks app

##Installation
1. Clone project 
    ```
    git clone https://jplans@bitbucket.org/jjdc/pos-be-inventory.git
    ```
2. Copy .env.example to .env and change the configs you need
    ```
    cp .env.example .env
    ```
3. Run the project
    ```
    docker-compose up -d
    ```
4. Install laravel dependencies
    Connect to app container
    ```
    docker-compose exec php sh
    ```
    Run composer command
    ```
    composer install
    ```
5. Setup mysql
    Connect to mysql container
    ```
    docker-compose exec mysql sh
    ```
    This will avoid mysql connection error on laravel connection
    ```
    ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
    ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'root';
    ```
6. Run laravel migration and seeder
    Connect to app container
    ```
    docker-compose exec php sh
    ```
    Execute laravel commands
    ```
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    ```

Visit http://localhost:8091/

##Docker Container Credentials
```
DB Host: mysql
DB Port: 3306
DB Name: pos_inventory_db
DB Username: root
DB Password: root
```

##Important
Run this query to be able to connect the app to db
```
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'root';
```

Note: port must be 3306 in order to connect on mysql container
this is a little bit confusing on docker-compose settings port which is forwarded to 33062.
Port (33062) will be use to connect mysql via sql editor.

Connect to app container
```
docker-compose exec php sh
```
Run laravel migration and seeder
```
php artisan migrate
php artisan db:seed
```

