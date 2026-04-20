<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActiviteLog;
use App\Models\User;

class ActiviteLogController extends Controller
{
    public function index(Request $request)
    {
       $users = User::all();
       $logs=ActiviteLog::where('action','connexion')->orderBy('created_at', 'desc')->get();
       if ($request->filled('user_id')) {
            $logs = $logs->where('user_id', $request->input('user_id'));
        }
       return view('log.index', compact('logs', 'users'));
    }
}
