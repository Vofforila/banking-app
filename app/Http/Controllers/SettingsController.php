<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    public function index()
    {
        return view(view: 'partials.settings-heading');
    }
}
