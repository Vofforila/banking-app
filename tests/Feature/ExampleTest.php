<?php
//
//use App\Models\Transactions;
//use Illuminate\Http\UploadedFile;
//
//test('Test CSV Output', function () {
//    $filePath = base_path('tests/Data/bankstatement.csv');
//    $outfilePath = base_path('tests/Data/bankstatementout.json');
//
//    $file = new UploadedFile(
//        $filePath,
//        'bankstatement.csv',
//        null,
//        true
//    );
//
//    $expected = json_decode(file_get_contents($outfilePath), true);
//
//    $response = $this->post('/import', [
//        'statement' => $file,
//    ]);
//
//    $response->assertStatus(200);
//    $response->assertExactJson($expected);
//
//    $this->assertDatabaseHas('transactions', [
//        'iban' => 'RO02RNCB0188167102130001',
//        'currency' => 'RON',
//    ]);
//
//    expect(Transactions::count())->toBe(23);
//
//});
