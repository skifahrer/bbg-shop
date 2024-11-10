# bbg-shop

## Description
Eshop application for bbg. 

### Features
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
- communication with database is done through Doctrine ORM
- database structure is created with Doctrine annotations on Entities so no need to write SQL queries
- every change in database structure is done through migrations
- communication with frontend is done through API which can be found in `src/ApiResource` folder
- user registration and login are done through API with JWT tokens
- every request that is related to user data contains JWT token in header and is authorized via JWT guard
- api documentation can be found on `/api/` route which is automatically generated from code by Api Platform. Documentation is swagger based and can be used for testing API endpoints.


## Creation of the app

1. Install Symfony CLI:

```bash
    brew install symfony-cli/tap/symfony-cli
```

2. Create a new Symfony app:

```bash
   symfony new --webapp bbg-shop
```
    `
3. Start the server to see if everything is working in development:

```bash
    symfony server:start
```

4. Create DB structure.

User can has multiple charts and multiple orders. Cart can have only one user, but can contain multiple products with quantiites. Order is same as chart, on top of that contains final price and shipping and invoice address.




## TODOs

- implement built-in Symfony password hashing
- frontend
