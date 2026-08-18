<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_static'];

    protected $casts = [
        'is_static' => 'boolean',
    ];
}
