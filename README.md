# Tickets

## Installation

- Clone Repo
- Create a Database called `tickets`
- Clone `.env.example` to `.env` and add your database credentials
- Open a Terminal / Command Prompt window at repo root
- Run `composer install`
- Run `php artisan migrate`
- Run `php artisan db:seed`
- Run `php artisan serve` or run a XAMPP / NGINX / Apache server yourself
- Navigate to `http://localhost` / `http://localhost:3000` / `http://localhost:8000` or `/public` if the public folder is not configured on your server (check your server requirements as defined by the server documentation)
- If you get the Laravel Welcome page then the site is running successfully
- For additional tests run `./vendor/bin/phpunit` from repo root

## Usage

You will need Postman or another API testing utility for this to work as the API URLs are setup using POST

- Open Postman
- Set the method type to `POST`
- Navigate to any of the following URLs:

```
http://localhost/api/tickets/open
http://localhost/api/tickets/closed
http://localhost/api/tickets/view/{id}
http://localhost/api/users/{emailAddress}/tickets
http://localhost/api/stats
```

You can obtain a ticket ID for view by getting a tickets.id from the tickets table
You can obtain a user's email addressing for user tickets by getting a users.email from the users table
