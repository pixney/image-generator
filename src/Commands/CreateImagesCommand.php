<?php
// TODO: Cleanup. Make sure we use all the dependencies. Do we need @c?

namespace Pixney\StatamicImageGenerator\Commands;

use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;
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
            $this->info("\n\n" . 'Looking for assets within :' . $dir);

            $foundAssets = AssetContainer::find('assets')->assets($dir, true);

            if (!$foundAssets->count()) {
                $this->warn('Could not find any files to create within: ' . $dir);
                continue;
            }

            $validAssets = collect();

            foreach ($foundAssets as $key => $asset) {
                if ($asset->isImage() && !in_array($asset->extension(), $extExceptions)) {
                    $validAssets->push($asset);
                }
            }

            if (!$validAssets->count()) {
                $this->warn('Could not find any files to create. Potentially becuase found files file extensions are within configuration exception settings.');
                continue;
            }

            $this->info('Found ' . $validAssets->count() . ' Images to create.');
            $this->output->progressStart($validAssets->count());

            $validAssets->each(function ($item, $key) use ($directories,$dir) {
                dispatch(new CreateImagesJob($item, $directories[$dir]));
                $this->getOutput()->progressAdvance();
            });

            $this->getOutput()->progressFinish();
        }
    }
}
