# github_search_app
Jednoduchá aplikace pro výpis repozitářů na webu GitHub.com postavená na Symfony 3.3
Pro práci s logy je nutné mít nainstalovanou databázi MySQL. Nastavení databáze je možné upravit v app/config/parameters.yml

Instalace: 
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
