# Baking App

This is a app where you can import your csv,pdf,img/jpeg and view the data statisticly structured. You can also
categroize your expenses by createign diffrent categories for exepnses/incomes gained.

- **Import bank statement**
- **Statistics**
- **User Auth**
- **Clean UI**
- **SQLite Database**

How to Run Locally:

```
composer install
php artisan optimize:clear
npm install
php artisan migrate
php artisan optimize:clear
php artisan key:generate
```

### Technologies

| Technologies                                               | Environments | Version |
|------------------------------------------------------------|--------------|---------|
| [PHP](https://www.php.net/)                                | Backend      | 8.4     |
| Web tehnologies                                            | Web          |         |
| [SQLite](https://sqlite.org/)                              | Database     | 18.2.0  |
| [Tailwindcss](https://tailwindcss.com/)                    | UI           | 4.2.2   |
| [Laravel](https://laravel.com/)                            | Backend      | 5.25.1  |
| [Livewire FluxUI](https://fluxui.dev/)                     | UI           | 2.13.1  |
| [TesseractOCR](https://github.com/tesseract-ocr/tesseract) | OCR          | 5       |
| [ImageMagick](https://imagemagick.org/#gsc.tab=0)          | Converstor   | 7.1.2   |

TO DO: Empty

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
10. Add accounts page
11. Add Transfer History
