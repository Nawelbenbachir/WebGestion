<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Societe;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', ['users' => $users]); //équivalent à compact('users')
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return "Formulaire de création d'un utilisateur";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return "Enregistrement d'un nouvel utilisateur";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return "Détail de l'utilisateur avec l'ID : " . $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return "Formulaire d'édition de l'utilisateur avec l'ID : " . $id;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return "Mise à jour de l'utilisateur avec l'ID : " . $id;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return "Suppression de l'utilisateur avec l'ID : " . $id;
    }
    
}
