<?php


namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The default values for the model's attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => 'customer',
    ];

    public function purchases()
    {
        return $this->belongsToMany(Product::class, 'purchases')
                    ->withPivot('quantity', 'purchased_at')
                    ->withTimestamps();
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user')
                    ->withPivot('is_read') // Đảm bảo có trạng thái 'is_read'
                    ->withTimestamps();
    }
    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }

}
