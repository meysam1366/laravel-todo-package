<?php

namespace meysammaghsoudi\todopackage\Models;

use Illuminate\Database\Eloquent\Model;
use meysammaghsoudi\Todopackage\Models\Label;
use meysammaghsoudi\Todopackage\Models\UserTodo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(UserTodo::class);
    }

    public function labels()
    {
        return $this->hasMany(Label::class);
    }
}
