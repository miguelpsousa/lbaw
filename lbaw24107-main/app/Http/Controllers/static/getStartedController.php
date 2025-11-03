<?php

namespace App\Http\Controllers\static;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class getStartedController extends Controller
{
    /**
     * Show the get started page. 
     */
    public function show()
    {
        

        // Check if the current user can see (show) the card.
        if(Auth::check()){
            return redirect('/dashboard');
        }
        else{
            return view('pages.static.getStarted');
        }
        
    }

}
