## Description
This is a simple vending machine simulation project. It allows users to select items, insert coins, and dispense products.

## Features
- Supports multiple products with prices.
- Handles coin insertion and change calculation.
- Basic inventory management.
- Has logic separated on 2 services, the one where the client interacts (public) and other with all the core logic of the project (api) which is more difficult to reach.

## Installation
1. Clone the repository: `git clone https://github.com/aihc94/vending-machine.git`
2. Navigate to the directory: `cd vending-machine`
3. Use the Makefile to obtain the recipes used to install the project. The flow will be:
    - Run "make build-prod" The image is heavy because needs mongoDb through symfony. Sorry for the wait.
    - Run "make up"
    - Run "make install"
    - If want a seeded database run "make init-database-with-seeders"
    - If want only database configuration "make init-database-without-seeders"
4. Go to http://localhost/purchase to insert money and purchase products
5. Go to http://localhost/service to add stock to the DDBB

