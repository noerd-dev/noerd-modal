<?php

namespace NoerdModal\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class NoerdModalServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'noerd');
        Livewire::addLocation(viewPath: __DIR__ . '/../../resources/views/components');

        // Publish built Vite assets
        $this->publishes([
            __DIR__ . '/../../dist/build' => public_path('vendor/noerd-modal'),
        ], 'noerd-modal-assets');

        // Auto-publish built assets if not exists
        $this->publishBuiltAssetsIfNotExist();
    }

    private function publishBuiltAssetsIfNotExist(): void
    {
        $targetPath = public_path('vendor/noerd-modal/manifest.json');
        $sourcePath = __DIR__ . '/../../dist/build/manifest.json';

        if (! File::exists($targetPath) && File::exists($sourcePath)) {
            File::ensureDirectoryExists(public_path('vendor/noerd-modal'));
            File::copyDirectory(__DIR__ . '/../../dist/build', public_path('vendor/noerd-modal'));
        }
    }
}
