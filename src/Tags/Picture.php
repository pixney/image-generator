<?php

namespace Pixney\StatamicImageGenerator\Tags;

use Statamic\Tags\Tags;
use Statamic\Facades\URL;
use Statamic\Support\Str;
use Pixney\StatamicImageGenerator\Jobs\CreateImagesFromTagJob;

class Picture extends Tags
{
    /**
     * {{ picture:image }}
     *
     * @return void
     */
    public function image()
    {
        $tag = explode(':', $this->tag, 2)[1];

        $asset = $this->context->value($tag);

        $extExceptions = config('image-generator.extExceptions');

        if (!$asset->isImage()) {
            echo 'Not an image.';
            // Log::info('Not an image.', [$asset->extension(), $extExceptions]);
            return;
        }
        if (in_array($asset->extension(), $extExceptions)) {
            echo 'Image extension exists in your exceptions.';
            // Log::info('Image extension exists in your exceptions.:', [$asset->extension(), $extExceptions]);
            return;
        }

        // TODO: Do we return another view if above fails?

        $extExceptions = config('image-generator.extExceptions');

        $directories = collect(config('image-generator.directories'))->keys();

        $sources = collect();

        foreach ($directories as $key => $directoryName) {
            if ($this->dirInPath($asset->path(), $directoryName)) {
                $directorySettings = config('image-generator.directories.' . $directoryName);
                $sources           = (new CreateImagesFromTagJob($asset, $directorySettings))->handle();
            }
        }

        $sources = $sources->map(function ($createdImage, $key) {
            foreach ($createdImage['paths'] as $format => $path) {
                $createdImage['paths'][$format] = URL::makeAbsolute($createdImage['paths'][$format]);
            }
            return $createdImage;
        });

        return view('image-generator::picture', [
            'sources'       => $sources,
            'fallback'      => $asset->absoluteUrl(),
            'asset'         => $asset,
            'class'         => $this->params->get('class'),
            'alt'           => $this->params->get('alt'),
            'attributes'    => $this->params->get('attributes'),
        ])->render();
    }

    private function dirInPath(string $path, string $dir)
    {
        if (Str::contains($path, $dir . '/')) {
            return true;
        }
        return false;
    }
}
