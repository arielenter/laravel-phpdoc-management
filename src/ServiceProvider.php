<?php

namespace Arielenter\LaravelPhpdocManagement;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public const string TRANSLATIONS = 'arielenter_laravel_phpdoc_management';
    
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', self::TRANSLATIONS);
    }
}
