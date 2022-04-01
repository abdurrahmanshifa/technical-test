<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
     public function index()
     {
               return view('pages.dashboard.index');
     }
    
     public function fileView($dir,$filename)
     {
         return response()->file(storage_path('app/public/'.$dir.'/'.$filename));
     }
}