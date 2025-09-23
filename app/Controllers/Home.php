<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Home - LMS'
        ];
        
        return view('home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About - LMS'
        ];
        
        return view('about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact - LMS'
        ];
        
        return view('contact', $data);
    }
}
