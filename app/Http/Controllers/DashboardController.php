<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');

        } elseif ($user->hasRole('terapis')) {
            return redirect()->route('terapis.dashboard');

        } elseif ($user->hasRole('kepala')) {
           return redirect()->route('kepala.dashboard');;
        }

        // Jika user tidak punya role (seharusnya tidak terjadi)
        Auth::logout();
        return redirect('/login')->withErrors('Akses ditolak.');
    }
}