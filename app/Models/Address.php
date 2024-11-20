<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'user_id',
        'province',
        'district',
        'ward',
        'full_address',  // Optional: store the entire address string
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}