# Laravel Admin Dashboard with Bootstrap 5.3

## Features

The Laravel Admin Dashboard is a web-based application that serves as a starting point for an Admin Dashboard panel, complete with User Management and Roles Permissions.

- Constructed using Laravel 10
- Incorporates Bootstrap 5.3
- Features an Authentication System
- Includes User Management with a Block/Unblock System
- Equipped with a Roles Permissions System
- Allows User Profile Viewing and Updating
- Enables User Password Changes
- More features to be added soon

## POS DSR transaction verification

POS users can manually verify pending DSR transactions until 12:00 noon on the day after each transaction. Any remaining pending transactions are automatically verified at that deadline in the application timezone (`Asia/Kolkata` by default). The scheduled command also catches up transactions whose deadline passed while the scheduler was unavailable.

The server must invoke Laravel's scheduler every minute for this to run:

```text
php artisan schedule:run
```

Configure that command in the server's cron or Task Scheduler, using the project directory as its working directory.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
