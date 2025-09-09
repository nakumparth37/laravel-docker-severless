<?php
// app/Jobs/RunSeedersJob.php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;

class RunSeedersJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $seeders;

    /**
     * @param array $seeders Array of seeder class names in order
     */
    public function __construct(array $seeders)
    {
        $this->seeders = $seeders;
    }

    public function handle()
    {
        foreach ($this->seeders as $seederClass) {
            Artisan::call('db:seed', [
                '--class' => $seederClass,
                '--force' => true, // force in production
            ]);
        }
    }
}
