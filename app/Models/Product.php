<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    // Define the properties if needed, like fillable fields
    protected $fillable = ['id', 'name', 'description',  'price', 'image', 'category', 'color', 'material', 'button'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    // In Product.php model
    public function types()
    {
        return $this->belongsToMany(Type::class, 'product_type', 'product_id', 'type_id');
    }

}
