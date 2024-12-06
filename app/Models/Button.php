<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Button extends Model
{
    //
    protected $table = 'button';
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
