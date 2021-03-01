<?php

namespace App\Http\Controllers\Masterapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
class HomeController extends ResourceController
{
    public function index()
    {
        return view('master_app.home.index');
    }
}
