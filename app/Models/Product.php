<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    // Define the properties if needed, like fillable fields
    protected $fillable = ['id', 'name', 'details',  'price', 'image', 'type_id', 'color', 'material', 'button'];

    public function image()
    {
        return $this->hasOne(ProductImage::class);
    }
    // In Product.php model
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    
}
