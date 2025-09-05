<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        // Load the template with home content
        return view('template');
=======
        return view('index');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
>>>>>>> 90cc1f5 (Added Home controller, routes, and views for basic navigation)
    }
}
