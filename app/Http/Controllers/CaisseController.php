<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use Illuminate\Http\Request;


class CaisseController extends Controller
{
    public function index()
    {

        return inertia('Caisse', [
            'clients' => Client::orderBy('lastname')->get(),
            'categories' => Category::all(),
        ]);
    }
}
