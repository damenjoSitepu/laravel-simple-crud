<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Home Base View
     *
     * @return View
     */
    public function index(): View
    {
        return view("public.home.index",[
            "title" => "Home",
        ]);
    }
}
