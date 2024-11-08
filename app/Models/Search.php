<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $fillable = ['busqueda_tagged','busqueda_fromdate','busqueda_todate', 'contador'];
}
