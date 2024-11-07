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
    public function types()
    {
        return $this->belongsToMany(Type::class, 'product_type', 'product_id', 'type_id');
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
    


}
