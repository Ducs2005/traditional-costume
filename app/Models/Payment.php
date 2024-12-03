<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';
    protected $fillable = [
        'transaction_id',
        'user_id',
        'money',
        'note',
        'code_vnpay',
        'code_bank',
        'time',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
