# CMS Notifications

This is a Laravel project built using PHP 8, SQLite, Vite, VueJs 3 and Inertia. It also contains [Github Actions](https://docs.github.com/en/actions/learn-github-actions) with a simple configuration for [CI/CD](https://en.wikipedia.org/wiki/CI/CD) in [run-tests.yml](https://github.com/caritoz/mini-crm/blob/master/.github/workflows/run-tests.yml).

## Requirements
* [Composer](http://getcomposer.org)
* [Git](http://git-scm.com)
* [PHP >= 8.1](http://php.net)
* [Laravel v10 Support](https://laravel.com/docs/10.x)
* [Pusher account](https://pusher.com/)

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Installation
1. Clone the repository:
```
git clone https://github.com/caritoz/cms-notificacions.git
cd cms-notificacions

```
2. Install the PHP dependencies:
```
composer install
```
3. Install the NPM dependencies:
```
npm install
```
4. Create a SQLite database file:
```
touch database/database.sqlite
```
5. Set the configurations in the .env file, copy and generates application keys:
```
cp .env.example .env
php artisan key:generate
```
7. Add your Pusher credentials to the .env file:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_app_cluster
```
Replace your_app_id, your_app_key, your_app_secret, and your_app_cluster with your Pusher credentials. You can get these credentials from the Pusher dashboard.

8. Finally, migrate the database, and run database seeds:
```
php artisan migrate --seed
```

## Development
To start the development server, run:
```
npm run dev
```
This will start the Vite development server and compile the assets. The development server will watch for changes in your code and automatically reload the page.

To run the PHP server (the output will give the address):
```
php artisan serve
```
This will start the PHP server and allow you to access the project at http://localhost:8000.

In Addition, to run the PHP jobs, run:
```
php artisan queue:work --queue=highest,high,default,low,lowest --tries=1
```

To deploy the project, you can follow the Laravel deployment guide. Make sure to configure your environment variables and set the correct permissions for the storage and bootstrap/cache directories.

You're ready to go! Visit CMS Notifications in your browser, and login with:

- **Username:** johndoe@example.com
- **Password:** secret

and open in other browser, and login with:
- **Username:** samdoe@example.com
- **Password:** secret

## Testing
To run the tests, use the following command:
```
vendor/bin/pest
```
or you can run as well:
```
php artisan test
```
This will run all the tests located in the tests directory.

You can also run specific tests or test suites by specifying the file or directory path:
```
vendor/bin/pest tests/Unit/
```

## Contributions
Contributions are welcome! Please create a pull request with your changes.

## License

This project is licensed under the [GNU General Public License v3.0](https://github.com/caritoz/cms-notificacions/blob/main/LICENSE).
