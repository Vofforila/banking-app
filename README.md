# banking-app

How to Compile:

composer install
php artisan optimize:clear
npm install
php artisan migrate
php artisan optimize:clear
php artisan key:generate

TO DO:

Features:

1. Modular Theme Selections
2. CSV Transaction Import
3. Add Manual Transaction
4. Delete Single/All Transactions
5. Statistics
6. Settings
7. User Auth
8. Add User-predefined Categories for each Expenses/Income
9. Add User Defined Categories

Vreau sa faci o aplicatie, sa zicem banking, dar nu atat de avansata. Sa ii dai extrasul bancar pe o perioada si sa iti
citeasca acel pdf, csv, etc si sa le stochezi in baza de date (sqlite). Sa le imparta pe categorii si sa iti faca si
niste statistici cu cheltuielile pe categorii, etc. Vreau sa folosesti laravel ca sa nu stai sa reinventezi roata.

Lucruri de bifat:
Authentificare/Inregistrare
Pagina de import pdf/csv
Lista cu toate tranzactiile
Pagina de statistici
Rapoarte pe ce ai cheltuit banii in functie de categorii

Sugestii de stack: Laravel 13, php 8.4+ ( poti folosii varianta free de herd.laravel.com , contine tot ce ai nevoie sa
ridici un laravel), packagist.org pt pachete de composer, pt admin panel poti folosii https://fluxui.dev/ ca si
framework visual.
