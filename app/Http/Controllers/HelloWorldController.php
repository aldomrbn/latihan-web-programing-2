<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    public function index()
    {
        return view('helloworld');
    }

    public function ambilFile()
    {
        return view('v_html.ambilfile');
    }
}