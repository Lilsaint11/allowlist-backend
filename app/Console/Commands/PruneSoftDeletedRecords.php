<?php

namespace App\Console\Commands;

use App\Models\Wallet;
use App\Models\Project;
use Illuminate\Console\Command;

class PruneSoftDeletedRecords extends Command
{
    protected $signature = 'app:prune-trash';
    protected $description = 'Permanently delete records soft-deleted more than 30 days ago';

    public function handle()
    {
        $cutoff = now()->subDays(30);

        $wallets = Wallet::onlyTrashed()->where('deleted_at', '<', $cutoff)->forceDelete();
        $projects = Project::onlyTrashed()->where('deleted_at', '<', $cutoff)->forceDelete();

        $this->info('Pruned old trashed records.');
    }
}