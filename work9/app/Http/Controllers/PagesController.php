<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function create()
    {
        return view('test.create');
    }

    public function baba(Request $request, $testID)
    {
        echo $testID; 
        echo "<br>";
        echo $request->test;
    }
}
