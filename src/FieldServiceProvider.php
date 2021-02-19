<?php

namespace BBSLab\NovaSlateField;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-slate-field', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-slate-field', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $files = collect(File::allFiles(__DIR__.'/../dist/fonts'))
            ->mapWithKeys(function (\SplFileInfo $file) {
                return [
                    $file->getRealPath() => public_path("fonts/{$file->getRelativePathname()}"),
                ];
            })
            ->toArray();

        $this->publishes($files, 'slate-fonts');
    }
}
