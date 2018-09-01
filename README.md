# Symfony Shop Example

This project is a simple system for managing products list. 

### Tech

The following libraries are used:

* [Symfony Framework](https://symfony.com/) - PHP Web Application framework
* [Sonata Admin](https://sonata-project.org/) - provides admin functional
* [Bootstrap](https://getbootstrap.com/) - toolkit for developing with HTML, CSS, and JS
* [Knp Paginator](https://github.com/KnpLabs/KnpPaginatorBundle) - Symfony paginator

### Requirements

- PHP 7.1.3+
- MySQL 5.1+
- Composer

### Installation

Clone the repository to working directory.

```sh
$ git clone https://github.com/abler98/symfony-shop-example.git
```

Configure environment file.

```sh
$ cd symfony-shop-example
$ cp .env.dist .env
```

Configuration example for Homestead.

```env
APP_ENV=dev
APP_SECRET=secret
DATABASE_URL=mysql://homestead:secret@127.0.0.1:3306/symfony-shop
```

Install the dependencies and devDependencies.

```sh
$ composer install
```

Setup datebase and create shema from migrations.

```sh
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```

Optional you can load fixtures.

```sh
$ php bin/console doctrine:fixtures:load
```

Run the server (using built-in server).

```sh
$ php bin/console server:start *:8080
```

### Usage

- Go to the http://127.0.0.1:8080/admin/login
- Login using username & password (login credentials below)
- You will be redirected to the admin dashboard page
- Add categories & products
- Go to the main page and you will see a list of categories
- Click at category list item and you will see a list of products

### Login credentials

Username | Password | Roles
--- | --- | ---
root | root | ROLE_ADMIN
admin | admin | ROLE_ADMIN
