<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $articles = \App\Models\Article::latest()->get();
        return view('admin.index', compact('articles'));
    }
}
