<?php

namespace Tests;

use App\Http\Controllers\ImportController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (!Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }

//    protected function TestCsv($import ImportController)
//    {
//        $file = \test
//        $import->store($file);
//    }
}
