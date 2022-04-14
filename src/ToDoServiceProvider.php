<?php

namespace meysammaghsoudi\todopackage;

use Illuminate\Support\ServiceProvider;

class ToDoServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}
