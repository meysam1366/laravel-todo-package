<?php

namespace meysammaghsoudi\todopackage\Models;

use Illuminate\Database\Eloquent\Model;
use meysammaghsoudi\Todopackage\Models\Task;

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
