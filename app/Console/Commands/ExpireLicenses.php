<?php

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;

class ExpireLicenses extends Command
{
    protected $signature = 'licenses:expire';

    protected $description = 'Mark licenses whose expiry date has passed as expired';

    public function handle(): int
    {
        $count = License::whereNotNull('expiry')
            ->where('expiry', '<', now())
            ->whereIn('status', ['pending', 'active'])
            ->update(['status' => 'expired']);

        $this->info("Expired {$count} license(s).");

        return self::SUCCESS;
    }
}
