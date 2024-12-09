<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender',
        'content',
        'receiver_id',
        'receiver_type',
    ];

    /**
     * Get the user associated with the notification's receiver_id.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Scope a query to only include notifications for all users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAll($query)
    {
        return $query->where('receiver_type', 'all');
    }

    /**
     * Scope a query to include notifications for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('receiver_type', 'one')->where('receiver_id', $userId);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')
                    ->withPivot('is_read') // Đảm bảo có trạng thái 'is_read'
                    ->withTimestamps();
    }
    public function markAsReadByUser(User $user)
    {
        $this->users()->updateExistingPivot($user->id, ['is_read' => true]);
    }
}
