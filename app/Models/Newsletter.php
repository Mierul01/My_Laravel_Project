<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'image', 'restored_at'];

    protected $dates = ['deleted_at', 'restored_at'];
}
