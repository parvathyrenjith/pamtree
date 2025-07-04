<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
     protected $guard = 'admin';

    protected $fillable = ['name', 'email', 'password'];
}
