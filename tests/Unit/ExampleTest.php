<?php

use Illuminate\Http\UploadedFile;

test('Test CSV Output', function () {
    $filePath = base_path('tests/Data/RO02RNCB0188167102130001_2026-03-01_2026-03-31.csv');

    $file = new UploadedFile(
        $filePath,
        'transactions.csv',
        null,
        true
    );

    $response = $this->post('/import', [
        'file' => $file,
    ]);

    $response->assertOk();

    $response->assertSee('CSV file');

});
