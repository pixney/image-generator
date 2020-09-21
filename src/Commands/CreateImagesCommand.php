<?php
// TODO: Cleanup. Make sure we use all the dependencies. Do we need @c?

namespace Pixney\StatamicImageGenerator\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
use Statamic\Contracts\Assets\Asset;
use Statamic\Facades\AssetContainer;
use Illuminate\Support\Facades\Artisan;
use Statamic\Contracts\Assets\AssetRepository;
use Pixney\StatamicImageGenerator\Jobs\CreateImagesJob;
use Statamic\Contracts\Assets\AssetContainer as AssetsAssetContainer;

class CreateImagesCommand extends Command
{
    use RunsInPlease;

    protected $signature   = 'pixney:images:create';
    protected $description = 'Create images';

    public function handle(AssetRepository $assets, AssetsAssetContainer $c)
    {
        Artisan::call('statamic:glide:clear');

        $directories    = config('image-generator.directories');
        $extExceptions  = config('image-generator.extExceptions');
        $directoryNames = collect($directories)->keys();

        $this->getOutput()->progressStart(0);

        foreach ($directoryNames as $dir) {
            AssetContainer::find('assets')->assets($dir, true)
                ->filter(function (Asset $asset) use ($directories, $dir, $extExceptions) {
                    if ($asset->isImage() && !in_array($asset->extension(), $extExceptions)) {
                        dispatch(new CreateImagesJob($asset, $directories[$dir]));
                        $this->getOutput()->progressAdvance();
                    }
                });
        }

        $this->getOutput()->progressFinish();
    }
}
