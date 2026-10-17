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

POS users can manually verify pending DSR transactions at any time. The “Verify all” action verifies every pending transaction for that POS matching the currently selected report filters, including transactions on later pages. The scheduled command automatically verifies pending transactions dated before the current day after 12:00 noon in the application timezone (`Asia/Kolkata` by default). It retries every minute after noon and catches up transactions whose deadline passed while the scheduler was unavailable.

The server must invoke Laravel's scheduler every minute for this to run:

```text
php artisan schedule:run
```

Configure that command in the server's cron or Task Scheduler, using the project directory as its working directory.

## Transaction change history

Wallet transactions, payment summaries, and customer orders record who created, changed, edited, or deleted a record in `transaction_status_changes`. Status tables show the responsible Admin, POS, customer, or automatic system and the time of the change; expandable history shows earlier events and edited field names. Historical records created before this audit table was deployed are labeled as having unavailable actor history rather than being attributed speculatively. Apply the migration with `php artisan migrate`.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
