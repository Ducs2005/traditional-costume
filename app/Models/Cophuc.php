<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cophuc extends Model
{
    use HasFactory;
    
    protected $table = 'cophuc';
    
    protected $fillable = [
        'name',
        'description',
        'detail',
        'image',
    ];
}
