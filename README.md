# bbg-shop

## Description


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
