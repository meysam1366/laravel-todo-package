<?php

namespace meysammaghsoudi\todopackage\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{

    protected $fillable = [
        'name'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
