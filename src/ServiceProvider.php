<?php

namespace Pixney\StatamicImageGenerator;

use Statamic\Events\AssetUploaded;
use Pixney\StatamicImageGenerator\Tags\Picture;
use Statamic\Providers\AddonServiceProvider;
use Pixney\StatamicImageGenerator\Listeners\CreateImagesOnUpload;

class ServiceProvider extends AddonServiceProvider
{

    protected $publishAfterInstall = false;

    protected $listen = [
        AssetUploaded::class => [
            CreateImagesOnUpload::class,
        ],
    ];

    protected $tags = [
        Picture::class
    ];

    protected $commands = [
        \Pixney\StatamicImageGenerator\Commands\CreateImagesCommand::class,
    ];

    function boot(): void
    {
        parent::boot();

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('image-generator.php'),
            ], 'image-generator-configuration');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'image-generator');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/image-generator'),
        ], 'image-generator-views');
    }

   

}
