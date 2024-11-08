<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_id',
        'title',
        'link',
        'tags',
        'creation_date',
    ];
}

