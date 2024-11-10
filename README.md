
# bbg-shop

## Description
The **bbg-shop** is proof of concept for take-home assignment. It includes features such as user registration, login, product listing, product details, cart, checkout, order history, and order summary. The app is developed using Symfony.

## Quick Start

```bash
  docker-compose up
```

If running for the first time, execute the seeder to load the products:

```bash
  docker compose --profile seeder run seeder
```

You can access the app on [`localhost`](http://localhost) or [`localhost:8000`](http://localhost:8000).

## Features
The frontend is built using pure JavaScript, HTML, CSS, and Symfony Twig templates, which facilitates better translation management. The frontend communicates with the backend via API. The backend is built with Symfony and the API Platform extension, using Doctrine ORM for database communication. The database consists of a simple relational structure with six tables.

Here is a simplified example of a flow diagram of the application:
![Flow Diagram](/public/images/diagram.svg)

### Key Features on Frontend:
- User registration
- User login and logout
- Product listing with pagination
- Product details
- Locale support across the e-shop
- Product search with locale support
- User cart with add and remove functionality
- Checkout process with shipping and billing address
- Option to adjust product quantity in cart and checkout
- Order summary and order history
- Automatic saving of cart items and checkout fields on the server

### Key Features on Backend:
- Communication with the database via Doctrine ORM
- Database structure created using Doctrine annotations on entities, eliminating the need for SQL queries
- Database structure changes managed through migrations
- API communication with the frontend, located in the `src/ApiResource` folder
- User registration and login managed via API with JWT tokens
- Each request related to user data includes a JWT token in the header, authorized via JWT guard
- API documentation is available on the `/api/` route, automatically generated by the API Platform. It is Swagger-based and can be used for testing API endpoints.

## Development
To develop this application, you will need to install Symfony CLI, Composer, and Docker on your machine. It is recommended to run the app locally in watch mode using Symfony CLI, with the database running via Docker Compose.

### Database Structure
For the database structure, refer to the `src/Entity` folder. We use Doctrine ORM to define the database schema, which allows centralized schema management. Migrations are generated using Symfony CLI.
![Database Diagram](/public/images/db.svg)

The database has a simple structure:
- `User`: Can have multiple `Cart`, `Checkout`, and `Order` entries.
- `Cart`: Belongs to one `User`, can contain multiple `ItemQuantity`, and may be part of one `Checkout`.
- `Checkout`: Linked to one `Cart` and one `User`, and includes `Shipping` and `Invoice` addresses.
- `Order`: Belongs to one `User`, can include multiple `ItemQuantity`, and contains a final price along with `Shipping` and `Invoice` addresses. Similar to `Checkout` but is immutable and contains comprehensive data.
- `ItemQuantity`: Contains one `Product` and its quantity, and can be part of both a `Cart` and an `Order`.

### Application Structure

- `/assets`: Contains assets for the API Platform
- `/src`: Contains all PHP code
- `/public`: Contains public files such as images, CSS, and JavaScript
- `/templates`: Contains Twig templates
- `/translations`: Contains translations
- `/vendor`: Contains dependencies
- `/var`: Contains cache, logs, and sessions (excluded from Git)
- `/bin`: Contains console commands
- `/config`: Contains configuration files
- `/migrations`: Contains database migrations
- `/tests`: Contains test files

Within `/src`:
- `ApiResource`: API resources (API controllers)
- `Controller`: Non-API controllers
- `DataFixtures`: Database fixtures (for seeding)
- `Entity`: Database entities (tables)
- `Enum`: Constants also represented in the database
- `EventListener`: Event listeners (e.g., for locale changes)
- `Repository`: Database query repositories
- `Security`: Security classes (guards and authenticators, mainly for JWT)
- `Twig`: Twig extensions (e.g., for locale changes)

### Endpoint Documentation
The API documentation is available on the `/api` route, generated automatically by the API Platform. It is Swagger-based and can be used for testing API endpoints. Access it at [`localhost/api`](http://localhost/api) or [`localhost:8000/api`](http://localhost:8000/api).

### Requirements
To install the app's dependencies:

1. Install Symfony CLI (macOS):

```bash
    brew install symfony-cli/tap/symfony-cli
```

2. Install Composer (macOS):

```bash
    brew install composer
```

3. Install Docker on your machine:

```bash
    brew install docker
```

### Starting the App for Development
To start the app for development:

1. Install dependencies:

```bash
    composer install
```

2. Run the database:

```bash
    docker compose up db
```

3. Run migrations:

```bash
    symfony console doctrine:migrations:migrate
```

4. Seed the database:

```bash
    symfony console doctrine:fixtures:load
```

5. Start the server:

```bash
    symfony server:start
```

6. Run the fixture seeder if not done before:

```bash
    php bin/console doctrine:fixtures:load
```

7. Open the app in your browser at `localhost:8000`

### Running Tests
To run tests:

1. Run tests:

```bash
    symfony php bin/phpunit
```

### Useful Development Commands
Here are some useful commands for development:

- Create a new Symfony app:

```bash
   symfony new --webapp bbg-shop
```

- Create a new controller:

```bash
   symfony console make:controller
```

- Create a new entity:

```bash
   symfony console make:entity
```

- Create and run a migration based on the entity:

```bash
   symfony console make:migration
   symfony console doctrine:migrations:migrate
```

## Known Issues, Todos, and Limitations
- Implement built-in Symfony password hashing
- Refactor templates for better code structure (move and merge JavaScript)
- Simplified registration: users are logged in automatically without email validation
- Login tokens expire quickly and are not refreshed
- No email functionality; password reset is unavailable
- No admin panel for product management
- No user profile page
- No product management page; products are seeded in the database
- Limited product image functionality; only one image in the public folder
- Only one unit test provided as an example
