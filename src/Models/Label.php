<?php

namespace mmaghsoudi\todopackage\Models;

class Label
{

    protected $fillable = [
        'name'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
