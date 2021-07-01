<p align="center"><img src="https://raw.githubusercontent.com/paajake/invoicegen-test/master/public/images/logo/logo.png" width="400"></p>
<h1 align="center"><b>Invoice</b>Gen</h1>
<p align="center">
<a href="https://www.codefactor.io/repository/github/paajake/invoicegen-test"><img src="https://www.codefactor.io/repository/github/paajake/invoicegen-test/badge" alt="CodeFactor" /></a>
<a href="https://codeclimate.com/github/paajake/invoicegen-test/maintainability"><img src="https://api.codeclimate.com/v1/badges/55657735fb1258fe4ae4/maintainability" /></a>
<a href="https://travis-ci.org/paajake/invoicegen-test"><img src="https://travis-ci.org/paajake/invoicegen-test.svg?branch=master" alt="Build Status"></a>
<a href="https://codecov.io/gh/paajake/invoicegen-test">
  <img src="https://codecov.io/gh/paajake/invoicegen-test/branch/master/graph/badge.svg" />
</a>    
</p>

## About
This is a Test app to help the Finance department of a Law Firm, generate invoices for their clients based on billable hours garnered by their Lawyers.

## Installation
This is a PHP based application with Laravel Framework for Backend and AdminLTE for Frontend 

### Requirements
- LEMP stack with PHP 7.2 minimum
- <a href="https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos">Composer</a> installed
- _*Note:* CDNs are used for Frontend dependencies and thus no node or npm installations are required._

### Steps
   1. Download or Clone the repo into your web directory and point your webserver to the `public` folder as the app's root directory
   2. Rename `.env.example` to `.env`
   3. Create a new mysql database and update `.env` with it's name and access credentials
   4. OPTIONAL : If you'd prefer to use `S3` for file storage, add this configuration line `FILESYSTEM_DRIVER=s3` to your `.env` file and update the AWS section of the `.env`
   5. Run `php artisan storage:link` to create a public accessible point to the app's file storage.
   6. If you'd need password reset, fill the SMTP section of the `.env` file
   7. From a terminal, change directory to the root folder of the project and run `composer install` to install the dependencies of the app, and run `composer artisan key:generate` when successfully completed to generate an encryption key for the app.
   8. Run `php artisan migrate --seed` to create the database structure of the app in mysql, and seed it with dummy data.
   9. Visit the base url of the app in a browser and login with this credential `Email : admin@invoicegen.test ` and `Password : admin1234`
