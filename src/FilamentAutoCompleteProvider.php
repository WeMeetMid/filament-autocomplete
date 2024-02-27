<?php

namespace Meeftah\FilamentAutoComplete;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentAutoCompleteProvider extends PackageServiceProvider
{
    public static string $name = 'filament-autocomplete';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)->hasViews();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            assets: [
                AlpineComponent::make('filament-autocomplete', __DIR__.'/../resources/js/filament-autocomplete.js'),
            ],
            package: 'meeftah/filament-autocomplete'
        );
    }
}
