<?php

// TODO: Cleanup protected stuff.

namespace Pixney\StatamicImageGenerator\Jobs;

use Statamic\Facades\Image;
use Illuminate\Bus\Queueable;
use Statamic\Contracts\Assets\Asset;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $asset;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $directorySettings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Asset $asset, array $directorySettings = null)
    {
        $this->asset             = $asset;
        $this->directorySettings = $directorySettings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $imageManipulator            = Image::manipulate($this->asset);
        $directorySettingsCollection = collect($this->directorySettings);

        $directorySettingsCollection->each(function ($settings, $directoryName) use ($imageManipulator) {
            $imageFormats = collect($settings['formats']);
            $params = collect($settings['params']);

            // Set image parameters
            $params->each(function ($val, $p) use ($imageManipulator) {
                $imageManipulator->setParam($p, $val);
            });

            // Create an image for each format.
            $imageFormats->each(function ($format, $key) use ($imageManipulator) {
                $imageManipulator->setParam('fm', $format);
                $imageManipulator->build();
            });
        });
    }
}
