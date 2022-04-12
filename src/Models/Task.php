<?php

namespace mmaghsoudi\todopackage\Models;

class Task
{
    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labels()
    {
        return $this->hasMany(Label::class);
    }
}
