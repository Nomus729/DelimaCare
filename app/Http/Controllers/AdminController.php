<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Pastikan file ini ada di resources/views/admin/index.blade.php
        return view('admin.index');
    }
}
