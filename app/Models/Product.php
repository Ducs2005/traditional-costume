<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    // Define the properties if needed, like fillable fields
    protected $fillable = ['name', 'description', 'price', 'image_path']; // Exclude id and category if not relevant

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // In Product.php model
    // Product.php
    // In Product.php
    public function type()
    {
        return $this->belongsTo(Type::class); // A product belongs to one type
    }


    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function button()
    {
        return $this->belongsTo(Button::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    // In Product model
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');  // Assuming 'seller_id' is the foreign key for the user
    }


    

}
