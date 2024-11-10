<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'type';
    protected $fillable = ['id', 'name'];


    // Type.php
    // In Type.php
    public function products()
    {
        return $this->hasMany(Product::class); // A type has many products
    }   


}
