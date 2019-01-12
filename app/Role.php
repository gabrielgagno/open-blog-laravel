<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['role'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function permissions()
    {
        return $this->hasMany('App\Permission');
    }
}
