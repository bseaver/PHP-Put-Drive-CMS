# Power a Silex, Twig Website from Google Drive CMS

#### By Benjamin T. Seaver

## Description

Prototype PHP site whose data is supplied by Google Drive CMS see https://github.com/bseaver/google-drive-cms

## Initial Goals:
* Do the minimum PHP coding to demonstrate a website based upon data collected into a JSON object by Google Drive CMS.

* Use Silex for routing with 1's based array indices for dynamic routes.

* Use Twig templates for each route.

* Update the website's content with a post route.

## Setup Requirements
* See https://secure.php.net/ for details on installing _PHP_.  Note: PHP is typically already installed on Mac.
* See https://getcomposer.org/ for details on installing _composer_.

## Installation Instructions
* Clone project.
* From project root, run $ `composer install --prefer-source --no-interaction`
* To run website using installed _PHP_:
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
* 2017 Benjamin T. Seaver

## License
* MIT

## Implementation Plan

* Install dependencies (composer.json, composer.lock, .gitignore)
* Create Silex framework (web/index.php, app/app.php)
* Create PUT or POST route to receive json input
* Create GET routes to output data
* Style a little to engage imagination
* Cache images stored on Google User Content on website to avoid rate limits

End specifications
