<?php
// TODO: Cleanup the protected stuff..

namespace Pixney\StatamicImageGenerator\Jobs;

use Statamic\Facades\Image;
use Illuminate\Bus\Queueable;
use Statamic\Contracts\Assets\Asset;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateImagesFromTagJob implements ShouldQueue
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
    protected $directorySettings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Asset $asset, $directorySettings)
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
        if (!$this->asset->isImage()) {
            return;
        }
        $imageManipulator = Image::manipulate($this->asset);

        $sources = collect();

        $directorySettingsCollection = collect($this->directorySettings);

        $directorySettingsCollection->each(function ($settings, $settingName) use ($imageManipulator, $sources) {
            $imageFormats = collect($settings['formats']);
            $params = collect($settings['params']);
            $imagePaths = collect();

            // Set image parameters
            $params->each(function ($val, $p) use ($imageManipulator) {
                $imageManipulator->setParam($p, $val);
            });

            // Create an image for each format.
            $imageFormats->each(function ($format, $key) use ($imageManipulator, $imagePaths) {
                $imageManipulator->setParam('fm', $format);
                $path = $imageManipulator->build();
                $imagePaths->put($format, $path);
            });

            $sources->put($settingName, collect([
                'paths'   => $imagePaths,
                'params'  => $settings['params'],
                'formats' => $settings['formats'],
                'queries' => $settings['queries'],
            ]));
        });

        return $sources;
    }
}
