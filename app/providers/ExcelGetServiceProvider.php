<?php

use Illuminate\Support\ServiceProvider;

class ExcelGetServiceProvider extends ServiceProvider{

    public function register()
    {
        $this->app->bind('ExcelGet', function(){
            return new ExcelGet();
        });
    }
} 