<?php

namespace App\Http\Controllers\Rollie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $route  = 'rollie.home';
    public function index()
    {
        return view($this->route.'.index');
    }
}
