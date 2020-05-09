<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUri extends Model
{
    public $timestamps = false;
    public $table = "admin_uris";
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
