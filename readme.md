# forum

a simple forum created using 
<a href="http://laravel.com">laravel</a> framework

## Installation

### Step 1.

> To run this project, you must have PHP 7.4 installed as a prerequisite.

Begin by cloning this repository to your machine, and installing all Composer dependencies.

```bash
git clone git@github.com:adnanahmady/forum.git
cd forum && composer install
cp .env.example .env
php artisan key:generate
```

### Step 2.

Next, create a new database and reference its name and username/password within the project's `.env` file. In the example bellow, we've named the database, "homestead".

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4.

Until an administration portal is available, manually insert any number of "channels" (think of these as forum categories) into the "channels" table in your database.

Once finished, clear your server cache, and you're all set to go!

```bash
php artisan cache:clear
```

### Step 5.

Use your forum, Visit `http://forum.test/threads` to create a new account and publish your first thread.

