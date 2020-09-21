<?php

namespace Pixney\StatamicImageGenerator\Listeners;

use Statamic\Events\AssetUploaded;
use Pixney\StatamicImageGenerator\Jobs\CreateImagesJob;

class CreateImagesOnUpload
{
    public function handle(AssetUploaded $event): void
    {
        $asset          = $event->asset;
        $directories    = config('image-generator.directories');
        $extExceptions  = config('image-generator.extExceptions');
        $directoryNames = collect($directories)->keys();

        if (!$asset->isImage()) {
            return;
        }

        if (in_array($asset->extension(), $extExceptions)) {
            return;
        }

        if (!config('statamic.assets.image_manipulation.cache')) {
            return;
        }

        foreach ($directoryNames as $dir) {
            if ($asset->isImage() && !in_array($asset->extension(), $extExceptions)) {
                dispatch(new CreateImagesJob($asset, $directories[$dir]));
            }
        }
    }
}
