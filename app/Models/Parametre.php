<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parametre extends Model
{
    use HasFactory;

    protected $fillable = [
        'derniere_societe', 
    ];
}
