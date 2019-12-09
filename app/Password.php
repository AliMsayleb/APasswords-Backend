<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $fillable = [
        'website', 'account', 'password', 'id', 
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
