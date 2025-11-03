<?php

namespace App\Http\Controllers\static;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class contactUsController extends Controller
{
    /**
     * Show the get started page. 
     */
    public function show(): View
    {
        return view('pages.static.contactUs',['title' => 'Contact Us']);
    
    }

}