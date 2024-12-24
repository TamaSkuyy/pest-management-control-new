# Pest Management Control System

## Overview

Pest Management Control System is a web application built with Laravel. It provides various features and functionalities to manage and interact with different modules.

## Features

- User Authentication
- Profile Management
- Data Management
- Customizable Templates

## Installation

1. Clone the repository:

   ```sh
   git clone https://github.com/yourusername/your-repo.git
   cd your-repo
   ```

2. Install dependencies:

   ```sh
   composer install
   npm install
   ```

3. Copy the [.env.example](http://_vscodecontentref_/0) file to [.env](http://_vscodecontentref_/1) and configure your environment variables:

   ```sh
   cp .env.example .env
   ```

4. Generate the application key:

   ```sh
   php artisan key:generate
   ```

5. Run the migrations:

   ```sh
   php artisan migrate
   ```

6. Start the development environment using Laravel Sail:

   ```sh
   ./vendor/bin/sail up
   ```

7. Install Laravel Octane and start the server with FrankenPHP:
   ```sh
   composer require laravel/octane
   php artisan octane:install --server=frankenphp
   php artisan octane:start
   ```

## Usage

- Access the application at `http://localhost`
- Register or log in to start using the app

## Contributing

Feel free to contribute to the project by submitting issues or pull requests.

## License

This project is licensed under the MIT License.
