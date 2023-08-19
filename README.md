# Blogs

This repository contains the source code for a blogging system. Follow these steps to set up and run the project on your local environment.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- [Docker](https://docs.docker.com/get-docker/): Docker is used to create, deploy, and run applications in containers.

- [Node.js](https://nodejs.org/): Node.js is a JavaScript runtime for executing JavaScript code outside of a web browser.

- [npm](https://www.npmjs.com/): npm is the package manager for Node.js.

- [Composer](https://getcomposer.org/): Composer is a dependency manager for PHP.

## Installation

### Step 1: Clone the Repository

Clone this repository to your local machine.

```shell
git clone git@github.com:filiptalev/laravel-blog.git
```
### Step 2: Navigate to the api Directory
Change your working directory to the api directory within the cloned repository.

```shell
cd api
```
### Step 3: Copy the Environment Configuration
Copy the provided .env.example file to .env. This file contains environment-specific configuration settings for your application.

```shell
cp .env.example .env
```
### Step 4: Install Composer Dependencies and Laravel Sail
Use Docker to install Composer dependencies and Laravel Sail, which is a development environment for Laravel applications.

```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

```
### Step 5: Enter the Docker Image

Access the Docker image where your application is running. This allows you to run commands within the Docker container.
```shell
docker exec -it api-laravel.test-1 bash
```

### Step 6: Run Database Migrations
Inside the Docker container, execute database migrations to set up the database schema for the blog system.
```shell
php artisan migrate
```

### Step 7: Seed the Database
Seed the database with user accounts. This step creates two user accounts:

- Admin User: admin@example.com (admin role)

- Regular User: user@example.com (user role)

- Password for both: 123123123

```shell
php artisan db:seed
```

### Step 8: Exit the api Directory
Exit the api directory and return to the project's root directory.

```shell
exit
```
### Step 9: Navigate to the cms Directory
Change your working directory to the cms directory within the project.

```shell
cd ../cms
```

### Step 10: Install Node.js Modules
Install the required Node.js modules and dependencies for the frontend of the blog system.
```shell
npm install
```

### Step 11: Copy the Environment Configuration (Frontend)
Copy the provided .env.example file to .env within the cms directory. This file contains environment-specific configuration settings for the frontend of the blog system.
```shell
cp .env.example .env
```

### Step 12: Build Frontend Assets
Build the frontend assets for the blog system. Ensure you are using Node.js version 15.14.0 and npm version 7.7.6.
```shell
npm run dev
```

## All Set
Congratulations! You have successfully set up the blogging system on your local environment. You are now ready to start developing and using the application. If you encounter any issues or have questions, please refer to the documentation or seek assistance from the project's maintainers. Enjoy blogging!