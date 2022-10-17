<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearCachedItemData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item-data:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached ItemData. Used for after making changes to ItemData.json';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Cache::forget('itemData');
        return Command::SUCCESS;
    }
}
