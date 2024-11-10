# bbg-shop

## Description
Eshop application for bbg application. It is a simple eshop application with user registration, login, product listing, product detail, cart, checkout, order history and order summary. It is built with Symfony and pure Javascript.

## Quick start

```bash
  docker-compose up
```

If you are running it first time, run the seeder to load the products:

```bash
  docker compose --profile seeder run seeder
```

You can access the app on [`localhost`](http://localhost) or alternatively on [`localhost:8000`](http://localhost:8000)

## Features
Frontend is built with pure Javascript, html, css and symfony twig templates. We are storing frontend in twig templates for better translations management. Frontend communicates with backend through API. Backend is built with Symfony and Api Platform extension. It uses Doctrine ORM for database communication. Database is a simple relational database with 6 tables. 

Here is simplified example of an flow diagram of the application:
![Flow Diagram](/public/images/diagram.png)

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

## Development
If you are going to develop this application, you need to have installed Symfony CLI, Composer and Docker on your machine. I recommend running the app localy in watch mode with Symfony CLI and database via docker compose.

### Database structure
For DB structure see `src/Entity` folder. We use Doctrine ORM for setting up the database structure. This approach has advantage of having schema on one place and everything is generated from it. Generating migrations is done through Symfony CLI.
![Database Diagram](/public/images/db.svg)

Our DB has simple structure:
`User` can have multiple `Cart`, multiple `Checkout` and multiple `Order`.
`Cart` can have only one `User`, but can contain multiple `ItemQuantity` and can be part of one `Checkout`.
`Checkout` can have only one `Cart` and one `User`. It has also `Shipping` and `Invoice` `Address`.
`Order` can have only one `User` and can contain multiple `ItemQuantity`. It has also `FinalPrice` and `Shipping` and `Invoice` `Address`. It is similar to `Checkout` but it is immutable, and contains maximum data.
`ItemQuantity` can have only one `Product` with its quantity. It can be part of one `Cart` and one `Order`.


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

### Endpoint documentation
You can find the API documentation on `/api` route. It is automatically generated from the code by Api Platform. It is swagger based and can be used for testing API endpoints.
localhost:8000/api or localhost/api

alternatively on [`localhost/api`](http://localhost/api) or on [`localhost:8000/api`](http://localhost:8000/api)  

### Requirements
Here you will find how to instal all the requirements for the app.

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
Here you will find how to start the app for development.

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

4. Seed the database:

```bash
    symfony console doctrine:fixtures:load
```

4. Start the server:

```bash
    symfony server:start
```

5. Run fixtures seeder if you havent done it before:

```bash
    php bin/console doctrine:fixtures:load
```

5. Open the app in your browser on `localhost:8000`


### Running tests
Here you will find how to run the tests.

1. Run the tests:

```bash
    symfony php bin/phpunit
```

#### Good one time commands for development
Here I am addinf some useful commands for development.

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
- refactor the templates, for better code structure (javsacript should be moved and merged)
- registration is simplified, after yoy fill the form, you are automatically logged in without any email validation
- login token is not refreshed after some time and has short time to live
- there is no email sending, so no password reset is possible
- there is no admin panel for managing the products
- there is no user profile page
- there is no product management page, products are seeded in the database
- there is no product image upload only one image present in the public folder
- we have only one unit test as an example
