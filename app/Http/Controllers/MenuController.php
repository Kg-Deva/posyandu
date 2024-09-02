<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function dashboard()
    {
        return view('admin-page.dashboard');
    }

    public function beranda()
    {
        return view('admin-page.beranda');
    }
    public function tambahberanda()
    {
        return view('actions-admin.tambah-beranda');
    }
}
