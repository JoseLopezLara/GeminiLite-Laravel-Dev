# GeminiLite (Developer enviroment) Documentation

> **🦸🏽‍♀️🛠️🪚⚙️🦸🏽 YOU CAN CONTRIBUTE TO THIS PROJECT, CONTACT ME TO: [jose.lopez.lara.cto@gmail.com](mailto:jose.lopez.lara.cto@gmail.com) 🦸🏽‍♀️🛠️🪚⚙️🦸🏽**

[![Latest Stable Version](https://img.shields.io/packagist/v/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)
[![Stars](https://img.shields.io/github/stars/LiteOpenSource/GeminiLite-Laravel)](https://github.com/LiteOpenSource/GeminiLite-Laravel)
[![License](https://img.shields.io/packagist/l/liteopensource/gemini-lite-laravel)](https://packagist.org/packages/liteopensource/gemini-lite-laravel)

Gemini-Lite is a PHP SDK designed to interact with Gemini endpoints. This repository focuses solely on the package's development. To test the package in a Laravel project, visit the following repository and seed the full documentation: ✨<https://github.com/LiteOpenSource/GeminiLite-Laravel>✨.

## Table of Contents

1. [Get Started](#get-started)
   - [Generate vendor files](#generate-vendor-files)
     - [First: In the Root Directory of the Package](#first-in-the-root-directory-of-the-package)
     - [Second: In the Root Directory of the Package](#second-in-the-root-directory-of-the-package)
   - [Run project](#run-project)
   - [Execute test](#execute-test)
2. [Usage](#usage)
   - [Publish All Files](#publish-all-files)
   - [Publish Specific Files](#publish-specific-files)
     - [Configuration File](#configuration-file)
     - [Seeder for Roles and Limits](#seeder-for-roles-and-limits)
     - [Migrations](#migrations)
   - [API Key](#api-key)
   - [Modify the package](#modify-the-package)
3. [Requirements](#requirements)
4. [Configuration](#configuration)
5. [Bugs](#bugs)
6. [License](#license)

## Get Started

### Generate vendor files

#### First: In the Root Directory of the Package

The package is located at ```/packages/liteopensource/gemini-lite-laravel/```. This step is crucial to generate the internal vendor directory for the package.

```bash
composer install
composer dump-autoload
```

#### Second: In the Root Directory of the Package

Run the following commands:

```bash
composer install
composer dump-autoload`
```

### Run project

Run the project like a any other laravel project

```bash
php artisan migrate --seed
php artisan optimize:clear
php artisan serve 
```

### Execute test

> By the moment, the project doesn't has unit testing, the test are GET HTTP request tha you can run from PostMan or same. The all test are into ```app/Http/Controllers``` in adition, you can see ```web.php``` file.

```http
GET /testGeminiJSONMode HTTP/1.1
Host: http://127.0.0.1:8000/
```

## Usage

### Publish All Files

To publish all files, including migrations, seeders, and configuration files, run:

```bash
php artisan vendor:publish --provider="LiteOpenSource\GeminiLiteLaravel\Src\Providers\GeminiLiteServiceProvider"
```

### Publish Specific Files

#### Configuration File

```bash
php artisan vendor:publish --tag="geminilite-config"
```

#### Seeder for Roles and Limits

```bash
php artisan vendor:publish --tag="geminilite-limit-tokes"
```

#### Migrations

⚠️ **This command is currently not functional. Use the provider to publish all files to include migrations.**

```bash
php artisan vendor:publish --tag="migrations"  # Do not use this command
```

### API Key

Generate an API key and add it to your `.env` file:

```env
GEMINILITE_SECRET_API_KEY="YOUR_API_KEY"
```

### Modify the package

1. First: Remove the package from the `vendor` directory (`liteopensource/gemini-lite-laravel`).
2. Secont: When you are done to modify the package, you should run next comands first in directory of the package and then into directory of the project.

```bash
composer update
composer dump-autoload
```

## Requirements

You have to verify have added in your project:

- php: Minimum version ^8.0
- guzzlehttp/guzzle: Minimum version ^7.0
- illuminate/console: Minimum version ^9.0
- illuminate/database: Minimum version ^9.0
- illuminate/http: Minimum version ^9.0
- illuminate/support: Minimum version ^9.0

## Configuration

⚠️ **Token limit functionality is not yet available.**

Steps to configure:

1. Run:

```bash
composer install
composer dump-autoload
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --tag="geminilite-config"
```

3. Add your API key to the `.env` file:

```env
GEMINILITE_SECRET_API_KEY="YOUR_API_KEY"
```

## Bugs

**Legend:**

- 🔴 Unresolved
- 🟡 In Progress
- 🟢 Resolved

| Bug ID      | Status   | Description                                       |
|-------------|----------|---------------------------------------------------|
| #00001      | 🔴       | I need to add a validation for temperature levels and specify the allowed values for each model. For instance, the Pro model cannot accept certain temperature values, while the Flash model does.     |

## License

*MIT License*

*Copyright (c) 2025 José López Lara*

*Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “liteopensource/gemini-lite-laravel”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:*

*THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.*
