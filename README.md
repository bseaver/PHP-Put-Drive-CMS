# Explore receiving Google Drive CMS Published data in PHP


#### By Benjamin T. Seaver and David Quisenberry

## Description

Prototype PHP site whose data is supplied by Google Drive CMS see https://github.com/dmqpdx/google-drive-cms

## Project Requirements:

## Setup Requirements
* See https://secure.php.net/ for details on installing _PHP_.  Note: PHP is typically already installed on Mac.
* See https://getcomposer.org/ for details on installing _composer_.

## Installation Instructions
* Clone project.
* From project root, run $ `composer install --prefer-source --no-interaction`
* To run website using installed _PHP_ (better error messages):
    * From `web` folder in project, run $ `php -S localhost:8000`.
    * In web browser open `localhost:8000`.

## Known Bugs
* No known bugs

## Support and contact details
* No support

## Technologies Used
* PHP
* Composer
* Silex
* Twig
* Bootstrap
* HTML
* CSS
* Git

## Copyright (c)
* 2017 Benjamin T. Seaver, David Quisenberry

## License
* MIT

## Implementation Plan

* Install dependencies (composer.json, composer.lock, .gitignore)
* Create Silex framework (web/index.php, app/app.php)
* Create PUT or POST route to receive json input
* Create GET routes to output data

End specifications
