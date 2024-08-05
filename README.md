# Email Content Processing API

This Laravel project parses raw email content collected by Sendgrid, extracts the plain text body content, and saves it into a database. It also provides a RESTful API for managing these email records, protected by authentication.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)

## Requirements

- PHP >= 8.0
- Composer
- MySQL
- Laravel 9.x

## Installation

1. **Clone the repository:**

    ```sh
    git clone https://github.com/Anayln07/email-parse.git
    cd email-parse
    ```

2. **Install dependencies:**

    ```sh
    composer install
    ```

3. **Set up environment variables:**

    Copy `.env.example` to `.env` and update the necessary environment variables.

    ```sh
    cp .env.example .env
    ```

4. **Generate application key:**

    ```sh
    php artisan key:generate
    ```
5. **Install Laravel Sanctum:**

    ```sh
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    ```
    
## Configuration

Update the `.env` file with your database configuration and other required settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

1. **Run database migrations:**

    ```sh
    php artisan migrate
    ```
    
2. **Insert User:**

    ```sh
    php artisan db:seed
    ```

## Running the Application

1. **Start the local development server:**

    ```sh
    php artisan serve
    ```
    
2. **Schedule the command to run every hour:**

    Add the following line to your server's cron job configuration to run the parse:emails command every hour:

    ```sh
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```

## API Endpoints

The API is protected by Laravel Sanctum. Ensure you have a valid token to access these endpoints.

- Login:  ``POST /api/login``

```    
{
    "email": "johndoe@example.com",
    "password": "password"
}
```

- Headers

```
Authorization: Bearer {token}
```

### Email Management

- Store:  ``POST /api/emails``
- **Authorization: Bearer {token}**

1. **Request Body:**
    
    ```
    POST /api/emails
    ```
    
    ```
    {
        "affiliate_id": 1,
        "envelope": "Envelope Data",
        "from": "sender@example.com",
        "subject": "Test Subject",
        "email": "<html><body>From: example@example.com<br>To: recipient@example.com<br><br>This is a test email.</body></html>",
        "sender_ip": "192.168.1.1",
        "to": "recipient@example.com",
        "timestamp": 1722597339
    }
    ```

2. **Get by ID:**
    
    ```
    GET /api/emails/{id}
    ```
    
3. **Update:**
    
    ```
    PUT /api/emails/{id}
    ```
    
    ```
    {
        "affiliate_id": 1,
        "envelope": "Envelope Data",
        "from": "sender@example.com",
        "subject": "Test Subject",
        "sender_ip": "192.168.1.1"
    }
    ```

4. **Get All:**
    
    ```
    GET /api/emails
    ```
    

5. **Delete by ID:**
    
    ```
    DELETE /api/emails/{id}
    ```

## Testing

- Test the command using CLI:

```
php artisan emails:parse
```

- Run the tests using PHPUnit:

```
php artisan test
```

- **Note: This project is now set up to parse emails, save them to a database, and provide a RESTful API for managing email records. Ensure that your authentication tokens are used for protected routes to access the API endpoints.**
