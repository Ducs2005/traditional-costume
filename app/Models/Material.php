<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}