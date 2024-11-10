# bbg-shop

## Description
Eshop application for bbg application. It is a simple eshop application with user registration, login, product listing, product detail, cart, checkout, order history and order summary. It is built with Symfony and pure Javascript.

## Quick start

```
docker-compose up
```

## Features
#### Key features on FE:
- user registration
- user login and logout
- product listing with pagination
- product detail
- locale support across the eshop
- product search with locale support
- user cart with adding and removing products
- checkout process with shipping and invoice address
- option to change product quantity in cart and in checkout
- order summary and order history
- automatic saving of cart items, and checkout fields in server

#### Key features on BE: 
- communication with a database is done through Doctrine ORM
- database structure is created with Doctrine annotations on Entities so no need to write SQL queries
- every change in database structure is done through migrations
- communication with frontend is done through API which can be found in `src/ApiResource` folder
- user registration and login are done through API with JWT tokens
- every request that is related to user data contains JWT token in header and is authorized via JWT guard
- api documentation can be found on `/api/` route which is automatically generated from code by Api Platform. Documentation is swagger based and can be used for testing API endpoints.

## Database structure
For DB structure see `src/Entity` folder. We use Doctrine ORM for setting up the database structure. This approach has advantage of having schema on one place and everything is generated from it. Generating migrations is done through Symfony CLI.
![Database Diagram](/public/images/db.svg)

Our DB has simple structure:
`User` can have multiple `Cart`, multiple `Checkout` and multiple `Order`. 
`Cart` can have only one `User`, but can contain multiple `ItemQuantity` and can be part of one `Checkout`.
`Checkout` can have only one `Cart` and one `User`. It has also `Shipping` and `Invoice` `Address`.
`Order` can have only one `User` and can contain multiple `ItemQuantity`. It has also `FinalPrice` and `Shipping` and `Invoice` `Address`. It is similar to `Checkout` but it is immutable, and contains maximum data.
`ItemQuantity` can have only one `Product` with its quantity. It can be part of one `Cart` and one `Order`.

## Development
If you are going to develop this application, you need to have installed Symfony CLI, Composer and Docker on your machine. I recommend running the app localy in watch mode with Symfony CLI and database via docker compose.

### Structure of the app

`/assets` - conatains assets for Api platform
`/src` - contains all the PHP code
`/public` - contains all the public files like images, css, js
`/templates` - contains all the twig templates
`/translations` - contains all the translations
`/vendor` - contains all the dependencies
`/var` - contains all the cache, logs and sessions (not in git)
`/bin` - contains all the console commands
`/config` - contains all the configuration files
`/migrations` - contains all the migrations
`/tests` - contains all the tests

In the `/src` you can have find:
- `ApiResource` - contains all the API resources (api controllers)
- `Controller` - contains all the controllers (non api controllers)
- `DataFixtures` - contains all the fixtures for the database (seeding)
- `Entity` - contains all the entities (database tables)
- `Enum` - contains all the enums (constants also present in DB)
- `EventListner` - contains all the event listeners (we use for locale change)
- `Repository` - contains all the repositories (queries to DB)
- `Security` - contains all the security classes (guards, authenticators mostly for JWT)
- `Twig` - contains all the twig extensions (we use for locale change)



### Requirements
1. Install Symfony CLI (macOS):

```bash
    brew install symfony-cli/tap/symfony-cli
```
2. Install composer (macOS):

```bash
    brew install composer
```

3. Install Docker on your machine.
```bash
    brew install docker
```

### Starting app for development
1. Install dependencies:

```bash
    composer install
```

2. Run the database:

```bash
    docker compose up db
```

3. Run the migrations:

```bash
    symfony console doctrine:migrations:migrate
```

4. Start the server:

```bash
    symfony server:start
```

#### Good one time commands for development
Create a new Symfony app:

```bash
   symfony new --webapp bbg-shop
```

Create a new controller:

```bash
   symfony console make:controller
```

Create a new entity:

```bash
   symfony console make:entity
```

and create a new migration based on the entity:

```bash
   symfony console make:migration
```

when migration is created, run it:

```bash
   symfony console doctrine:migrations:migrate
```


## Known issues, todos and limitations
- implement built-in Symfony password hashing
- refac
