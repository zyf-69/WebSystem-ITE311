<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        return view('template.php'); // Entemsure this view exists in app/Views/
    }
}
